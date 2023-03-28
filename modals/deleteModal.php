<?php
$nameDelete = "";
if (isset($_GET['name_delete']))
    $nameDelete = $_GET['name_delete'];
?>


<!-- Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Видалення</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Ви дійсно бажаєте видалити <?php echo "$nameDelete"?> ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Скасувати</button>
                <button type="submit" id="btn_delete_ok" class="btn btn-danger">Видалити</button>
            </div>
        </div>
    </div>
</div>