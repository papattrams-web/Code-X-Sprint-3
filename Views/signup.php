<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Register Page</title>
    <!-- Same styling as login page - blue background with white form card -->
    <link rel="stylesheet" href="../css/loginandsignup.css">
    
</head>

<body>
    <!-- White card container centered on blue background -->
    <div class = "wrapper">
        <!-- Large heading -->
        <h1> Register </h1>
        <!-- Error message area - shows validation errors if form submission fails -->
        <p id ="error-message"></p>
        <!-- Registration form that collects user information -->
        <form id = "form" novalidate >   
            <!-- First name input with user icon -->
            <div>
                <label for ="firstname-input">
                    <!-- User/person icon SVG displayed in the blue label box -->
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill=black><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Z"/></svg>
                </label>
                <input type ="text" required name="firstname" id ="firstname-input" placeholder="First Name" />
            </div>

            <!-- Last name input with user icon -->
            <div>
                <label for ="lastname-input">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill=black><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Z"/></svg>
                </label>
                <input type ="text" required name="lastname" id ="lastname-input" placeholder="Last Name" />
            </div>
            
            <!-- Email input with @ symbol -->
            <div>
                <label for = "email-input">
                    <span>@</span> 
                </label>
                <input type ="email" required name="email" id ="email-input" placeholder="Email" />
            </div>

            <!-- Password input with lock icon -->
            <div>
                <label for ="password-input">
                    <!-- Lock icon SVG -->
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill=black><path d="M240-80q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h40v-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240Zm240-200q33 0 56.5-23.5T560-360q0-33-23.5-56.5T480-440q-33 0-56.5 23.5T400-360q0 33 23.5 56.5T480-280ZM360-640h240v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80Z"/></svg>
                </label>
                <input type="password" name="password" id ="password-input" placeholder="Password" />
            </div>

            <!-- Confirm password - user re-enters password to make sure they typed it correctly -->
            <div>
                <label for ="confirm-password-input">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill=black><path d="M240-80q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h40v-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240Zm240-200q33 0 56.5-23.5T560-360q0-33-23.5-56.5T480-440q-33 0-56.5 23.5T400-360q0 33 23.5 56.5T480-280ZM360-640h240v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80Z"/></svg>
                </label>    
                <input type="password" required name="confirm-password" id ="confirm-password-input" placeholder="Confirm Password" />
            </div>

            <!-- Submit button - validates and submits the form -->
            <button type="submit" id="signup-button" onclick="Validate(event)" >Sign Up</button>

            <!-- Link back to login page for existing users -->
            <p>Already have an account? <a href="login.php">Log In</a></p>
        

        </form>
    </div>
    
<!-- SweetAlert library for nice popup notifications -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Custom JavaScript that handles form validation and signup logic -->
<script src="../Views/signup.js" defer></script>
   
</body>
</html>