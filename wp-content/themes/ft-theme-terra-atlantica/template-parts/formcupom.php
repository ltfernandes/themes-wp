<form method="get" action="#">
    <div class="row mt-4 cupom">
        <div class="col-lg-10 col-12">
            <div class="input-group">
                <input type="text" class="form-control" name="cupom" id="cupom" placeholder="Possui cupom? Informe seu código" value="<?php echo $_GET['cupom']; ?>">
                <div class="input-group-append px-2">
                    <button class="btn btn-search" type="subnmit" id="button-addon1"></button>
                </div>
            </div>
        </div>
    </div>
</form>