<?php
include("lib/openCon.php");
/**
 * This script demonstrates how to autocorrect search terms by matching against
 * actual product descriptions in your database
 */

// Example search query with typos
$searchQuery = "hh OFIE children's swivel chai";

// Function to get closest matching product description
function findClosestProductDescription($query, $pdo) {
    // First, get all product descriptions from database
    $stmt = $pdo->prepare("SELECT pro_description_short FROM products");
    $stmt->execute();
    $allProducts = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    // Break query into words
    $queryWords = preg_split('/\s+/', strtolower($query));
    $bestMatch = null;
    $highestScore = 0;
    
    // For each product, calculate similarity score
    foreach ($allProducts as $productDesc) {
        // Initialize score for this product
        $score = 0;
        $productWords = preg_split('/\s+/', strtolower($productDesc));
        
        // For each word in the query, find closest match in product description
        foreach ($queryWords as $queryWord) {
            $bestWordMatch = null;
            $bestWordScore = 0;
            
            foreach ($productWords as $productWord) {
                // Calculate similarity between words
                similar_text($queryWord, $productWord, $similarity);
                
                // If this is a better match than previous best
                if ($similarity > $bestWordScore) {
                    $bestWordScore = $similarity;
                    $bestWordMatch = $productWord;
                }
            }
            
            // Contribute this word's best match score to total product score
            $score += $bestWordScore;
        }
        
        // Average the score by number of words in query
        $score = $score / count($queryWords);
        
        // If this product is a better match than previous best
        if ($score > $highestScore) {
            $highestScore = $score;
            $bestMatch = $productDesc;
        }
    }
    
    return [
        'original_query' => $query,
        'best_match' => $bestMatch,
        'confidence' => $highestScore
    ];
}

/**
 * Autocorrect by finding specific word replacements
 */
/*function autocorrectQueryUsingProductTerms($query, $pdo) {
    // Get all unique words from product descriptions
    $stmt = $pdo->query("SELECT DISTINCT pro_description_short FROM products");
    $allDescriptions = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    // Extract all unique words from descriptions
    $dictionaryWords = [];
    foreach ($allDescriptions as $desc) {
        $words = preg_split('/[\s\-_,\.\'\"]+/', $desc);
        foreach ($words as $word) {
            $word = trim(strtolower($word));
            if (strlen($word) > 1) { // Skip single characters
                $dictionaryWords[$word] = true;
            }
        }
    }
    $dictionaryWords = array_keys($dictionaryWords);
    
    // Break query into words
    $queryWords = preg_split('/\s+/', $query);
    $correctedWords = [];
    
    // Try to correct each word
    foreach ($queryWords as $queryWord) {
        $originalWord = $queryWord;
        $queryWord = strtolower($queryWord);
        
        // If word is already in our dictionary, keep it
        if (in_array($queryWord, $dictionaryWords)) {
            $correctedWords[] = $originalWord;
            continue;
        }
        
        // Find closest word in dictionary
        $bestMatch = null;
        $bestScore = 0;
        
        foreach ($dictionaryWords as $dictWord) {
            // Skip words with big length difference (optimization)
            if (abs(strlen($queryWord) - strlen($dictWord)) > 3) {
                continue;
            }
            
            similar_text($queryWord, $dictWord, $score);
            
            // If this is a better match
            if ($score > $bestScore) {
                $bestScore = $score;
                $bestMatch = $dictWord;
            }
        }
        
        // If we found a good match (over 70% similar)
        if ($bestScore > 70 && $bestMatch !== null) {
            // Preserve original capitalization if possible
            if (ctype_upper($originalWord)) {
                $correctedWords[] = strtoupper($bestMatch);
            } elseif (ucfirst($originalWord) === $originalWord) {
                $correctedWords[] = ucfirst($bestMatch);
            } else {
                $correctedWords[] = $bestMatch;
            }
        } else {
            // If no good match, keep original
            $correctedWords[] = $originalWord;
        }
    }
    
    return [
        'original' => $query,
        'corrected' => implode(' ', $correctedWords)
    ];
}*/

function autocorrectQueryUsingProductTerms($query, $pdo) {
    // Get all unique words from product descriptions
    $stmt = $pdo->query("SELECT DISTINCT pro_description_short FROM products");
    $allDescriptions = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Normalize words (remove umlauts, lowercase, trim special chars)
    function normalizeWord($word) {
        $map = ['ä' => 'ae', 'ö' => 'oe', 'ü' => 'ue', 'ß' => 'ss'];
        $word = mb_strtolower(trim($word), 'UTF-8');
        $word = strtr($word, $map);
        return preg_replace('/[^a-z0-9]/', '', $word); // remove non-alphanumerics
    }

    // Extract all unique words from descriptions
    $dictionaryWords = [];
    foreach ($allDescriptions as $desc) {
        $words = preg_split('/[\s\-_,\.\'\"]+/', $desc);
        foreach ($words as $word) {
            $word = normalizeWord($word);
            if (strlen($word) > 1) {
                $dictionaryWords[$word] = true;
            }
        }
    }
    $dictionaryWords = array_keys($dictionaryWords);

    // Break query into words
    $queryWords = preg_split('/\s+/', $query);
    $correctedWords = [];

    foreach ($queryWords as $queryWord) {
        $originalWord = $queryWord;
        $normalizedQueryWord = normalizeWord($queryWord);

        if (in_array($normalizedQueryWord, $dictionaryWords)) {
            $correctedWords[] = $originalWord;
            continue;
        }

        $bestMatch = null;
        $bestScore = 0;
        $bestDistance = PHP_INT_MAX;

        foreach ($dictionaryWords as $dictWord) {
            if (abs(strlen($normalizedQueryWord) - strlen($dictWord)) > 4) {
                continue;
            }

            similar_text($normalizedQueryWord, $dictWord, $similarity);
            $lev = levenshtein($normalizedQueryWord, $dictWord);

            // Combine score: prefer better similarity and shorter distance
            if (($similarity > 70 && $lev < $bestDistance) || $similarity > $bestScore) {
                $bestMatch = $dictWord;
                $bestScore = $similarity;
                $bestDistance = $lev;
            }
        }

        if ($bestMatch !== null && $bestScore > 65) {
            // Try to restore proper case
            if (ctype_upper($originalWord)) {
                $correctedWords[] = strtoupper($bestMatch);
            } elseif (ucfirst($originalWord) === $originalWord) {
                $correctedWords[] = ucfirst($bestMatch);
            } else {
                $correctedWords[] = $bestMatch;
            }
        } else {
            $correctedWords[] = $originalWord;
        }
    }

    return [
        'original' => $query,
        'corrected' => implode(' ', $correctedWords)
    ];
}


/**
 * Targeted correction for the specific example
 */
function correctSpecificSearchQuery($query) {
    $corrections = [
        'hj' => 'hjh',
        'offie' => 'OFFICE',
        'childen\'s' => 'children\'s'
    ];
    
    $words = preg_split('/\s+/', $query);
    $result = [];
    
    foreach ($words as $word) {
        $lowerWord = strtolower($word);
        if (isset($corrections[$lowerWord])) {
            $result[] = $corrections[$lowerWord];
        } else {
            $result[] = $word;
        }
    }
    
    return implode(' ', $result);
}

// Example usage with database
try {
    // Database connection
    /*$host = 'localhost';
    $dbname = 'your_database';
    $username = 'your_username';
    $password = 'your_password';
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);*/
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Method 1: Quick specific correction for the known case
    $corrected = correctSpecificSearchQuery($searchQuery);
    echo "Original: $searchQuery\n<br>";
    echo "Quick correction: $corrected\n\n<br>";
    
    // Method 2: General autocorrection using database terms
    $result = autocorrectQueryUsingProductTerms($searchQuery, $pdo);
    echo "General correction: {$result['corrected']}\n\n<br>";
    
    // Method 3: Find most similar product in database
    $closest = findClosestProductDescription($searchQuery, $pdo);
    echo "Most similar product: {$closest['best_match']}\n<br>";
    echo "Confidence: " . number_format($closest['confidence'], 2) . "%\n\n<br>";
    
    // Now search with the corrected query
    $stmt = $pdo->prepare("SELECT * FROM products WHERE pro_description_short LIKE :search");
    $searchTerm = '%' . $corrected . '%';
    $stmt->bindParam(':search', $searchTerm);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Found " . count($results) . " products matching corrected query.\n<br>";
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>