<div class="form-group mt-4">
    <button type="submit" class="btn btn-primary" name="submitButton" value="save">{{ ap_trans('common.buttons.'.(!empty($edit)?'save':'create')) }}</button>
    <button type="submit" class="btn btn-success" name="submitButton">
        {{ ap_trans('common.buttons.'.(!empty($edit)?'save':'create').'_and_exit') }}
    </button>
</div>
