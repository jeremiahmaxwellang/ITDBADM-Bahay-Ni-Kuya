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
        <form class="edit-property-form" action="sql_update_property.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $property['property_id'] ?>">
            
            <!-- Name -->
            <div class="form-group">
                <label for="name">Property Name:</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($property['property_name']) ?>" required>
            </div>

            <!-- Price -->
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" id="price" name="price" value="<?= htmlspecialchars(number_format((float)$property['price'], 2, '.', '')) ?>" required>
            </div>


            <!-- Address -->
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?= htmlspecialchars($property['address']) ?>" required>
            </div>

            <!-- Description -->
            <div class="form-group">
                <label for="description">Description:</label>
                <input type="text" id="description" name="description" value="<?= htmlspecialchars($property['description']) ?>" required>
            </div>
            
            <!-- Photo -->
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