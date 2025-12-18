<?php 
include('data.php');
include('functions.php');

$item = null;

if(isset($_GET["id"]) && is_numeric($_GET["id"])){
    $id = (int)$_GET['id'];
    if(isset($catalog[$id])){
        $item = $catalog[$id];
    }
}

if (!isset($item)) {
    header("location:drugCategories.php");
    exit();
}

$pageTitle = $item["title"];
$section = "drugCategories";
include('header.php');

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}
?>

<div class="details-container">
    <div class="drug-details-card">
        <div class="drug-details-header">
            <div class="back-nav">
                <a href="drugCategories.php" class="back-btn">← Back to Categories</a>
            </div>
        </div>
        
        <div class="drug-details-content">
            <div class="drug-image-section">
                <div class="drug-image-wrapper">
                    <img src="<?php echo htmlspecialchars($item["img"]); ?>" 
                         alt="<?php echo htmlspecialchars($item["title"]); ?>" />
                </div>
            </div>
            
            <div class="drug-info-section">
                <h1 class="drug-title"><?php echo htmlspecialchars($item["title"]); ?></h1>
                
                <div class="drug-meta">
                    <span class="category-badge">
                        <span class="badge-icon">📁</span>
                        <?php echo htmlspecialchars($item["category"]); ?>
                    </span>
                </div>
                
                <div class="info-table">
                    <div class="info-row">
                        <div class="info-label">📋 Definition</div>
                        <div class="info-value"><?php echo htmlspecialchars($item["definition"]); ?></div>
                    </div>
                    
                    <div class="info-row">
                        <div class="info-label">🏛️ Origin</div>
                        <div class="info-value"><?php echo htmlspecialchars($item["origin"]); ?></div>
                    </div>
                    
                    <div class="info-row">
                        <div class="info-label">💊 Examples</div>
                        <div class="info-value">
                            <ul class="examples-list">
                                <?php foreach($item["examples"] as $example): ?>
                                    <li><?php echo htmlspecialchars($example); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="info-row">
                        <div class="info-label">⚠️ Effects</div>
                        <div class="info-value">
                            <ul class="effects-list">
                                <?php foreach($item["effects"] as $effect): ?>
                                    <?php if(!empty(trim($effect))): ?>
                                        <li><?php echo htmlspecialchars($effect); ?></li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="info-row help-row">
                        <div class="info-label">🆘 Need Help?</div>
                        <div class="info-value help-text"><?php echo htmlspecialchars($item["help"]); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.info-table {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-top: 1.5rem;
}

.info-row {
    background: #f8fafc;
    border-radius: 0.75rem;
    padding: 1rem;
    border-left: 4px solid #0d9488;
}

.info-label {
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.info-value {
    color: #64748b;
    line-height: 1.7;
}

.examples-list,
.effects-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.examples-list li,
.effects-list li {
    background: #e2e8f0;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.85rem;
}

.effects-list li {
    background: #fee2e2;
    color: #991b1b;
}

.help-row {
    border-left-color: #10b981;
    background: #d1fae5;
}

.help-text {
    color: #065f46;
}
</style>

</div>

<?php include 'footer.php'; ?>
