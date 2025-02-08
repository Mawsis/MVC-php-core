<style>
.pagination-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 20px;
}

.pagination-button {
    padding: 10px 15px;
    margin: 0 5px;
    border: 1px solid #ccc;
    background-color: #f8f8f8;
    color: #333;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.2s;
}

.pagination-button:hover {
    background-color: #ddd;
}

.pagination-button.disabled {
    background-color: #e0e0e0;
    cursor: not-allowed;
}

.pagination-info {
    padding: 10px;
    font-weight: bold;
}
</style>
<div class="pagination-container">
    <?php if ($pagination['current_page'] > 1): ?>
    <a href="<?= $pagination['url'] . '?page=' . ($pagination['current_page'] - 1) ?>" class="pagination-button">«
        Previous</a>
    <?php else: ?>
    <span class="pagination-button disabled">« Previous</span>
    <?php endif; ?>

    <span class="pagination-info">Page <?= $pagination['current_page'] ?> of <?= $pagination['last_page'] ?></span>

    <?php if ($pagination['current_page'] < $pagination['last_page']): ?>
    <a href="<?= $pagination['url'] . '?page=' . ($pagination['current_page'] + 1) ?>" class="pagination-button">Next
        »</a>
    <?php else: ?>
    <span class="pagination-button disabled">Next »</span>
    <?php endif; ?>
</div>