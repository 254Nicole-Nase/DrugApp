<?php 
$pageTitle = "Drug Categories";
$section = "drugCategories";

include 'header.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

require_once("db_connection.php");

// Get all categories with drug count
$query = "SELECT c.category_id, c.category_name, COUNT(d.drug_id) as drug_count
          FROM drug_categories c
          LEFT JOIN drug_details d ON c.category_id = d.category_id
          GROUP BY c.category_id, c.category_name
          ORDER BY c.category_name";
$result = $conn->query($query);

// Define category icons
$categoryIcons = [
    'CNS Depressants' => '🍷',
    'CNS Stimulants' => '⚡',
    'Hallucinogens' => '🌀',
    'Dissociative Anesthetics' => '💫',
    'Narcotic Analgesics' => '💊',
    'Inhalants' => '💨'
];
?>

<div class="categories-container">
    <div class="page-header">
        <h1>📁 Drug Categories</h1>
        <p>Browse all drug categories in the database</p>
    </div>
    
    <div class="categories-grid">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($category = $result->fetch_assoc()): ?>
                <div class="category-card">
                    <div class="category-icon">
                        <?php echo isset($categoryIcons[$category['category_name']]) 
                              ? $categoryIcons[$category['category_name']] 
                              : '📁'; ?>
                    </div>
                    <h3><?php echo htmlspecialchars($category['category_name']); ?></h3>
                    <div class="category-stats">
                        <span class="drug-count">
                            <?php echo $category['drug_count']; ?> drug<?php echo $category['drug_count'] != 1 ? 's' : ''; ?>
                        </span>
                    </div>
                    <a href="dashboard.php#category-<?php echo $category['category_id']; ?>" class="btn btn-view">
                        View Drugs
                    </a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no-categories">No categories found.</p>
        <?php endif; ?>
    </div>
</div>

<style>
.categories-container {
    max-width: 1000px;
    margin: 0 auto;
}

.page-header {
    text-align: center;
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 2px solid #e2e8f0;
}

.page-header h1 {
    font-family: 'Space Mono', monospace;
    font-size: 2rem;
    color: #0f172a;
    margin-bottom: 0.5rem;
}

.page-header p {
    color: #64748b;
    font-size: 1.1rem;
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
}

.category-card {
    background: white;
    border-radius: 1rem;
    padding: 2rem;
    text-align: center;
    box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    border: 1px solid #e2e8f0;
    transition: all 0.25s ease;
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1);
}

.category-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.category-card h3 {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 1rem;
}

.category-stats {
    margin-bottom: 1.5rem;
}

.drug-count {
    display: inline-block;
    background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%);
    color: white;
    padding: 0.25rem 1rem;
    border-radius: 9999px;
    font-size: 0.875rem;
    font-weight: 500;
}

.no-categories {
    grid-column: 1 / -1;
    text-align: center;
    padding: 2rem;
    color: #64748b;
}
</style>

</div>

<?php 
$conn->close();
include 'footer.php';
?>
