<script type="module">
  // Import the functions you need from the SDKs you need
  import { initializeApp } from "https://www.gstatic.com/firebasejs/9.9.3/firebase-app.js";
  import { getAnalytics } from "https://www.gstatic.com/firebasejs/9.9.3/firebase-analytics.js";
  // TODO: Add SDKs for Firebase products that you want to use
  // https://firebase.google.com/docs/web/setup#available-libraries

  // Your web app's Firebase configuration
  // For Firebase JS SDK v7.20.0 and later, measurementId is optional
  const firebaseConfig = {
    apiKey: "AIzaSyBBYNYePAF67722gYX-7Tb4M-ASuUh5MLI",
    authDomain: "usman-rewari.firebaseapp.com",
    projectId: "usman-rewari",
    storageBucket: "usman-rewari.appspot.com",
    messagingSenderId: "549011817603",
    appId: "1:549011817603:web:c72c98a79647bfb353c7b6",
    measurementId: "G-2C5DSQ4L6Q"
  };

  // Initialize Firebase
  const app = initializeApp(firebaseConfig);
  const analytics = getAnalytics(app);
</script>