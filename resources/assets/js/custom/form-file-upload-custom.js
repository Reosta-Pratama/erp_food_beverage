(function () {

    "use strict";

    /* === Filespond === */
    FilePond.registerPlugin(
        FilePondPluginImagePreview,
        FilePondPluginImageExifOrientation,
        FilePondPluginFileValidateSize,
        FilePondPluginFileEncode,
        FilePondPluginImageEdit,
        FilePondPluginFileValidateType,
        FilePondPluginImageCrop,
        FilePondPluginImageResize,
        FilePondPluginImageTransform
    );

        /* === Single Upload === */
        FilePond.create(document.querySelector('.single-fileupload'), {
            labelIdle: `Drag & Drop your picture or <span class="filepond--label-action">Browse</span>`,
            imagePreviewHeight: 170,
            imageCropAspectRatio: '1:1',
            imageResizeTargetWidth: 200,
            imageResizeTargetHeight: 200,
            stylePanelLayout: 'compact circle',
            styleLoadIndicatorPosition: 'center bottom',
            styleButtonRemoveItemPosition: 'center bottom'
        });
        /* === Single Upload === */

        /* === Multiple Upload === */
        const MultipleElement = document.querySelector('.multiple-filepond');
        FilePond.create(MultipleElement,);
        /* === Multiple Upload === */
    /* === Filespond === */

    /* === Dropzone === */
    const myDropzone = new Dropzone(".dropzone");
    myDropzone.on("addedfile", file => {});
    /* === Dropzone === */

})();
