<?php get_header() ?>
<div class="card">
    <div class="card-header d-flex flex-grow-1 align-items-center">
        <p class="h4 m-0"><?= __('crud.label.edit') ?> <?php get_title() ?></p>
        <div class="right-button ms-auto">
            <?php 
            $params = ['table' => $tableName];
            if(isset($_GET['filter']))
            {
                $params['filter'] = $_GET['filter'];
            }
            ?>
            <a href="<?= routeTo('crud/index', $params) ?>" class="btn btn-warning">
                <?= __('crud.label.back') ?>
            </a>
        </div>
    </div>
    <div class="card-body">
        <?php if($error_msg): ?>
        <div class="alert alert-danger"><?=$error_msg?></div>
        <?php endif ?>
        <form action="" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <?php 
            foreach($fields as $key => $field): 
                $label = $field;
                $type  = "text";
                if(is_array($field))
                {
                    $field_data = $field;
                    $field = $key;
                    $label = $field_data['label'];
                    if(isset($field_data['type']))
                    $type  = $field_data['type'];
                }
                $label = _ucwords($label);
                $fieldname = $type == 'file' ? $field : $tableName."[".$field."]";
            ?>
            <div class="form-group mb-3">
                <label class="mb-2"><?=$label?></label>
                <?= \Core\Form::input($type, $fieldname, ['class'=>"form-control","placeholder"=>$label,"value"=>$old[$field]??$data->{$field}]) ?>
            </div>
            <?php endforeach ?>
            <div class="form-group">
                <button class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>
<?php get_footer() ?>
