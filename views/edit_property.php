<?php
// edit_property.php
    require_once('../includes/dbconfig.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Fetch property data
    $stmt = $conn->prepare("SELECT * FROM properties WHERE property_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $property = $result->fetch_assoc();
    
    if ($property) {
        ?>
        <form class="edit-property-form" action="update_property.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $property['property_id'] ?>">
            
            <div class="form-group">
                <label for="name">Property Name:</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($property['property_name']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="type">Property Type:</label>
                <select id="type" name="type" required>
                    <option value="Apartment" <?= $property['type']=='Apartment'?'selected':'' ?>>Apartment</option>
                    <option value="Dormitory" <?= $property['type']=='Dormitory'?'selected':'' ?>>Dormitory</option>
                    <option value="House" <?= $property['type']=='House'?'selected':'' ?>>House</option>
                    <option value="Room" <?= $property['type']=='Room'?'selected':'' ?>>Room</option>
                </select>
            </div>
            
            <!-- Include all other form fields similarly -->
            
            <div class="form-group">
                <label>Current Image:</label>
                <img src="../uploads/<?= $property['photo'] ?>" style="max-width:200px;display:block;margin:10px 0;">
            </div>
            
            <div class="form-group">
                <label for="image">New Image (leave blank to keep current):</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>
            
            <button type="submit" class="submit-btn">
                <i class="fas fa-save"></i> Update Property
            </button>
        </form>
        <?php
    } else {
        echo "<div class='alert alert-error'>Property not found</div>";
    }
} else {
    echo "<div class='alert alert-error'>No property ID specified</div>";
}
?>