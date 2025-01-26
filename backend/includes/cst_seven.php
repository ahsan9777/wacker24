<div class="col-md-6 col-12 mt-3">
    <label for="">Heading DE</label>
    <input type="text" required class="input_style" name="csec_heading_one_de" id="csec_heading_one_de" value="<?php print($csec_heading_one_de); ?>" placeholder="Heading DE">
</div>
<div class="col-md-6 col-12 mt-3">
    <label for="">Heading EN</label>
    <input type="text" class="input_style" name="csec_heading_one_en" id="csec_heading_one_en" value="<?php print($csec_heading_one_en); ?>" placeholder="Heading EN">
</div>
<div class="col-md-12 col-12 mt-3">
    <label for="">Detail DE</label>
    <textarea rows="5" type="text" class="input_style ckeditor_one_de" name="csec_content_one_de" id="csec_content_one_de" placeholder="Detail DE"><?php print($csec_content_one_de); ?></textarea>
</div>
<div class="col-md-12 col-12 mt-3">
    <label for="">Detail EN</label>
    <textarea rows="5" type="text" class="input_style ckeditor_one_en" name="csec_content_one_en" id="csec_content_one_en" placeholder="Detail EN"><?php print($csec_content_one_en); ?></textarea>
</div>
<div class="col-md-12 col-12 mt-3">
    <label for="">File One</label>
    <div class="">
        <label for="file-upload-one" class="upload-btn">
            <span class="material-icons">cloud_upload</span>
            <span>Upload Files</span>
        </label>
        <input id="file-upload-one" type="file" class="file-input" name="mFile_one">
    </div>
</div>
<div class="col-md-12 col-12 mt-3">
    <label for="">File Two</label>
    <div class="">
        <label for="file-upload-two" class="upload-btn">
            <span class="material-icons">cloud_upload</span>
            <span>Upload Files</span>
        </label>
        <input id="file-upload-two" type="file" class="file-input" name="mFile_two">
    </div>
</div>