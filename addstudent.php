<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <style>body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

header {
    background-color: #000;
    color: #fff;
    padding: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo img,
.user-icon img {
    height: 30px;
}

nav ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
    display: flex;
}

nav ul li {
    margin-right: 20px;
}

main {
    background-color: #fff;
    padding: 20px;
    margin-top: 20px;
}

h1 {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 10px;
}

p {
    color: #666;
    margin-bottom: 20px;
}

.grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.space-y > * {
    margin-bottom: 10px;
}

label {
    display: block;
    margin-bottom: 5px;
}

input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    margin-top: 5px;
}

button {
    background-color: #007bff;
    color: #fff;
    border: none;
    cursor: pointer;
    padding: 10px 20px;
    border-radius: 4px;
}

button:hover {
    background-color: #0056b3;
}
</style>
    <title>Account Settings</title>
</head>
<body>
    <div class="container">
        <header>
            <div class="logo">
                <img src="https://placehold.co/50x50" alt="Logo">
            </div>
            <nav>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Students</a></li>
                </ul>
            </nav>
            <div class="user-icon">
                <img src="https://placehold.co/30x30" alt="User Icon">
            </div>
        </header>
        <main>
            <h1>Account Settings</h1>
            <p>Change your account settings</p>
            <div class="grid">
                <div>
                    <h2>Account</h2>
                    <form action="account_settings.php" method="post">
                    <div class="space-y">
                        <div>
                            <label for="first_name">First Name</label>
                            <input type="text" name="first_name" value="<?php echo $userData['first_name']; ?>" required>
                        </div>
                        <div>
                        <label for="last_name">Last Name:</label>
                         <input type="text" name="last_name" value="<?php echo $userData['last_name']; ?>" required>

                        </div>
                        <div><label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo $userData['email']; ?>" required readonly>
</div>>

                    </div>
                </div>
                <div>
                    <h2>General Info</h2>
                    <div class="space-y">
                        <div>
                        <label for="subject">Subject:</label>
                         <input type="text" name="subject" value="<?php echo $userData['subject']; ?>" required>

                        </div>
                        <div>
                        <label for="address">Address:</label>
        <input type="text" name="address" value="<?php echo $userData['address']; ?>" required>
                        </div>
                        <div><input type="submit" name="change_account_info" value="Save Changes"></div>
                    </div>
                </div>
            </div>
            <div>
                <h2>Password</h2>
                <div class="space-y">
                    <div>
                    <label for="current_password">Current Password:</label>
        <input type="password" name="current_password" required>
                    </div>
                    <div>
                    <label for="new_password">New Password:</label>
        <input type="password" id="password" name="new_password" required><br>
                    </div>
                    <div>
                    <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                </div>
            </div>
            <div>
            <input type="submit" name="change_password" value="Change Password"  onclick="return validatePassword();">
   
            </div>
            </form>
        </main>
    </div>
</body>
</html>
