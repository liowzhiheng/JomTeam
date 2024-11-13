<?php

require("config.php");

// Insert records into the database
$sql = "INSERT INTO user (email, password, level)
VALUES ('Zhiheng@gmail.com', 'admin1', 1),
       ('Junxiang@gmail.com', 'admin1', 2),
       ('Wenchun@gmail.com', 'admin1', 2),
       ('Zeqing@gmail.com', 'admin1', 3),
       ('Yewyeong@gmail.com', 'admin1',3)";

if (mysqli_multi_query($conn, $sql)) {
    echo "<h3>New records created successfully</h3>";

    // Set a cookie after successful database insertion
    $cookie_name = "user_id";
    $cookie_value = mysqli_insert_id($conn); // Assuming user_id is auto-incremented primary key

    // Set the cookie to expire in 1 hour (3600 seconds)
    $cookie_expiration = time() + 3600; // current time + 1 hour

    setcookie($cookie_name, $cookie_value, $cookie_expiration, "/"); // "/" means cookie is available for the whole domain

    // Adding JavaScript for dynamic HTML demonstration
    echo "
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Create a new div element
            var newDiv = document.createElement('div');
            // Give your new div an id
            newDiv.id = 'dynamicContent';
            // Add some text content
            newDiv.innerHTML = '<p>Goodjob</p>';

            // Append the new div to the body
            document.body.appendChild(newDiv);

            // Add an event listener to change the content when clicked
            newDiv.addEventListener('click', function() {
                newDiv.innerHTML = '<p>The content has been changed after clicking!</p>';
            });
        });
    </script>
    ";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>
