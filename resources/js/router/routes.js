
const Routes = {
    defaults: {},
    routes: {
        "adminpanel.docs.content":{
            "uri": adminPrefix+"/docs",
            "methods":["POST"],
        },
        "adminpanel.settings.relation":{
            "uri": adminPrefix+"/settings/{name}/relation",
            "methods":["GET"],
        },
        "adminpanel.datatype.destroy":{
            "uri": adminPrefix+"/{datatype}/{id}",
            "methods":["DELETE"],
        },
        "adminpanel.datatype.relation":{
            "uri": adminPrefix+"/{datatype}/relation",
            "methods":["GET"],
        },
        "adminpanel.datatype.edit-field":{
            "uri": adminPrefix+"/{datatype}/edit-field",
            "methods":["POST"],
        },
        "adminpanel.datatype.update-field":{
            "uri": adminPrefix+"/{datatype}/update-field",
            "methods":["POST"],
        },
        "adminpanel.ajax":{
            "uri": adminPrefix+"/ajax/{method}",
            "methods":["POST"],
        },
        "adminpanel.ajax.getModal":{
            "uri": adminPrefix+"/ajax/getModal",
            "methods":["POST"],
        },
        "adminpanel.media.files":{
            "uri": adminPrefix+"/media/files",
            "methods":["POST"],
        },
        "adminpanel.media.rename":{
            "uri": adminPrefix+"/media/rename-file",
            "methods":["POST"],
        },
        "adminpanel.media.new-folder":{
            "uri": adminPrefix+"/media/new-folder",
            "methods":["POST"],
        },
        "adminpanel.media.delete":{
            "uri": adminPrefix+"/media/delete-file-folder",
            "methods":["POST"],
        },
        "adminpanel.media.move":{
            "uri": adminPrefix+"/media/move-file",
            "methods":["POST"],
        },
        "adminpanel.media.crop":{
            "uri": adminPrefix+"/media/crop",
            "methods":["POST"],
        },
        "adminpanel.media.upload":{
            "uri": adminPrefix+"/media/upload",
            "methods":["POST"],
        },
    },
    url: '',
    port: false
};

export { Routes };
