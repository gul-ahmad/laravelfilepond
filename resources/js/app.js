import "./bootstrap";

import Alpine from "alpinejs";

import * as FilePond from "filepond";
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
// Import the plugin code
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';

import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';


// Register the plugin
FilePond.registerPlugin(FilePondPluginImagePreview);
FilePond.registerPlugin(FilePondPluginFileValidateType);


import 'filepond/dist/filepond.min.css';

window.Alpine = Alpine;

window.FilePond = FilePond;

Alpine.start();
