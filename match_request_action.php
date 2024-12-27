<?php

require("config.php");

if (isset($_POST['accept_request_match'])) {
    $request_user_id = intval($_POST['request_user_id']);
    $request_match_id = intval($_POST['request_match_id']);

    // Update the friend request status
    $updateQuery = "UPDATE match_request SET status = 'accepted' WHERE request_user_id = $request_user_id AND match_id = $request_match_id";
    $updateStmt = $conn->prepare($updateQuery);
    $result = mysqli_query($conn, $updateQuery);

    $sql = "SELECT * FROM gamematch WHERE id = $request_match_id";
    $result2 = mysqli_query($conn, $sql);
    if ($result2->num_rows > 0) {
        $row = $result2->fetch_assoc();
        if ($row['current_players'] + 1 >= $row['max_players']) {
            $updateQuery2 = "UPDATE match_request SET status = 'rejected' WHERE match_id = $request_match_id AND status='pending'";
            $updateStmt2 = $conn->prepare($updateQuery2);
            $result3 = mysqli_query($conn, $updateQuery2);
        }
    }

    ?>
    <form name="my_form" action="join_match.php" method="POST">
        <input type="hidden" name="user_id" value="<?php echo $request_user_id ?>">
        <input type="hidden" name="match_id" value="<?php echo $request_match_id ?>">
    </form>
    <?php
}
if (isset($_POST['reject_request_match'])) {
    $request_user_id = intval($_POST['request_user_id']);
    $request_match_id = intval($_POST['request_match_id']);

    // Update the friend request status
    $updateQuery = "UPDATE match_request SET status = 'rejected' WHERE request_user_id = $request_user_id AND match_id = $request_match_id";
    $updateStmt = $conn->prepare($updateQuery);
    $result = mysqli_query($conn, $updateQuery);

    // Redirect to prevent duplicate submissions
    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $match_id);
    exit();
}
?>

<script>
    document.my_form.submit(); //automatically submits the form
</script>