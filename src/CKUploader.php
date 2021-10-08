<?php

namespace Fourn\AdminCK;

use Encore\Admin\Form\Field;

class CKUploader extends Field
{
    public static $js = [
        '/vendor/admin-ck/ckfinder/ckfinder.js',
    ];

    protected $view = 'admin-ck::ckuploader';

    public function render()
    {
        $filebrowserUploadUrl = route('ckfinder_connector');
        $this->script = <<<EOT
function selectFileWithCKFinder( elementId ) {
    CKFinder.config( { connectorPath: '{$filebrowserUploadUrl}' } );
    CKFinder.popup( {
        chooseFiles: true,
        onInit: function( finder ) {
            finder.on( 'files:choose', function( evt ) {
                var url = evt.data.files.first().getUrl();
                const parsed_url = new URL(url);
                const path = parsed_url.pathname;
                const splitted_path = path.split('/');
                const l = splitted_path.length;
                url = '/' + splitted_path[l-2] + '/' + splitted_path[l-1];
                document.getElementById( 'ckfinder-input-' + elementId ).value = url;
                document.getElementById( 'ckfinder-image-' + elementId).src = path;
            } );
            finder.on( 'file:choose:resizedImage', function( evt ) {
                document.getElementById( 'ckfinder-input-' + elementId ).value = evt.data.resizedUrl;
                document.getElementById( 'ckfinder-image-' + elementId).src = evt.data.resizedUrl;
            } );
        }
    } );
}
document.getElementById( 'ckfinder-popup-{$this->id}' ).onclick = function() {
    selectFileWithCKFinder('{$this->id}');
};
EOT;
        return parent::render();
    }
}
