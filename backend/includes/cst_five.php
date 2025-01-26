<div class="col-md-12 col-12 mt-3">
    <label for="">Year</label>
    <input type="number" onKeyPress="if(this.value.length==4) return false;" class="input_style" name="csec_year" id="csec_year" value="<?php print($csec_year); ?>" placeholder="Year">
</div>
<div class="col-md-6 col-12 mt-3">
    <label for="">Heading DE</label>
    <input type="text" required class="input_style" name="csec_heading_one_de" id="csec_heading_one_de" value="<?php print($csec_heading_one_de); ?>" placeholder="Heading DE">
</div>
<div class="col-md-6 col-12 mt-3">
    <label for="">Heading EN</label>
    <input type="text" class="input_style" name="csec_heading_one_en" id="csec_heading_one_en" value="<?php print($csec_heading_one_en); ?>" placeholder="Heading EN">
</div>
<div class="col-md-12 col-12 mt-3">
    <label for="">Detail One DE</label>
    <textarea rows="5" type="text" class="input_style ckeditor_one_de" name="csec_content_one_de" id="csec_content_one_de" placeholder="Detail One DE"><?php print($csec_content_one_de); ?></textarea>
</div>
<div class="col-md-12 col-12 mt-3">
    <label for="">Detail ONe EN</label>
    <textarea rows="5" type="text" class="input_style ckeditor_one_en" name="csec_content_one_en" id="csec_content_one_en" placeholder="Detail One EN"><?php print($csec_content_one_en); ?></textarea>
</div>
<div class="col-md-12 col-12 mt-3">
    <label for="">Detail Two DE</label>
    <textarea rows="5" type="text" class="input_style ckeditor_two_de" name="csec_content_two_de" id="csec_content_two_de" placeholder="Detail Two DE"><?php print($csec_content_two_de); ?></textarea>
</div>
<div class="col-md-12 col-12 mt-3">
    <label for="">Detail EN</label>
    <textarea rows="5" type="text" class="input_style ckeditor_two_en" name="csec_content_two_en" id="csec_content_two_en" placeholder="Detail Two EN"><?php print($csec_content_one_en); ?></textarea>
</div>
<div class="col-md-12 col-12 mt-3">
    <label for="">File</label>
    <div class="">
        <label for="file-upload-one" class="upload-btn">
            <span class="material-icons">cloud_upload</span>
            <span>Upload Files</span>
        </label>
        <input id="file-upload-one" type="file" class="file-input" name="mFile_one">
    </div>
</div>
<div class="col-md-12 col-12 mt-3">
    <label for="">File</label>
    <div class="">
        <label for="file-upload-two" class="upload-btn">
            <span class="material-icons">cloud_upload</span>
            <span>Upload Files</span>
        </label>
        <input id="file-upload-two" type="file" class="file-input" name="mFile_two">
    </div>
</div>