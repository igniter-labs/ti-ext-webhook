<div
    class="progress-indicator-container"
>
    <select
        id="<?= $field->getId('select') ?>"
        name="setup_event_code"
        class="form-control"
        data-request="onLoadSetupInstructions"
        data-progress-indicator="<?= lang('admin::lang.text_loading') ?>"
        autocomplete="off"
    >
        <option value=""><?= e(lang('admin::lang.text_please_select')) ?></option>
        <?php foreach ($formModel->getDropdownOptions() as $value => $option) { ?>
            <?php if (!is_array($option)) $option = [$option]; ?>
            <option
                <?= $value == $field->value ? 'selected="selected"' : '' ?>
                value="<?= $value ?>">
                <?= e(is_lang_key($option[0]) ? lang($option[0]) : $option[0]) ?>
                <?php if (isset($option[1])): ?> - <?= $option[1] ?><?php endif ?>
            </option>
        <?php } ?>
    </select>
</div>
<div
    class="card card-body bg-white markdown"
    data-partial="setup-instructions-content"
></div>