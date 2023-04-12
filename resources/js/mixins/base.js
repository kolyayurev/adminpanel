// import {messages} from './messages'

export default{
    // mixins: [messages],
    // data(){
    //     return{
    //         prLoading: true,
    //         // errors: [],
    //     }
    // },
    // mounted(){
    //     this.prLoading = false
    //     // this.displayErrors()
    // },
    methods:{
        // startLoading(){
        //     this.prLoading = true
        // },
        // stopLoading(){
        //     this.prLoading = false
        // },
        // checkUndefined(field){
        //     return typeof field === 'undefined'
        // },
        // async displayErrors(){
        //     if(this.errors.length)
        //     {
        //         this.errors.forEach((element,index) => {
        //             setTimeout(() => { this.warningMsg('Внимание',element,2000*(index+1),0); }, 500);

        //         });
        //     }
        // },
        // test(callback){
        //     callback()
        // },
        // baseAxios(url,data,
        //     successCallback     = (r) => { this.successMsg(); },
        //     errorCallback       = (r) => { this.errorMsg('Не удалось',r.data.message);},
        //     defaultCallback     = (r) => { this.warningMsg(); },
        //     catchCallback       = (e) => { console.log(e); this.errorMsg(); },
        //     finallyCallback     = (r) => {}
        // ){
        //     this.startLoading()
        //
        //     axios
        //         .post(url, data)
        //         .then(response => {
        //             switch (response.data.status) {
        //                 case 'success':
        //                     successCallback(response)
        //                     break;
        //                 case 'error':
        //                     errorCallback(response)
        //                     break;
        //                 default:
        //                     defaultCallback(response)
        //             }
        //         })
        //         .catch(error => {
        //             catchCallback(error)
        //         })
        //         .finally(res =>  {
        //             this.stopLoading()
        //             finallyCallback()
        //         })
        // },
        storagePath(file){
            return storage+file;
        },
        temporaryPath(path){
            return path + "?" + new Date().getTime();
        },
        printObject(obj){
            return JSON.stringify(obj);
        },
        getFileName: function (name) {
            if (typeof name !== 'string')
                return ;

            name = name.split('/');
            return name[name.length - 1];
        },
        fileIs(file, type) {
            if (typeof file !== 'undefined') {
                if (typeof file === 'string') {
                    if (type === 'image') {
                        return this.endsWithAny(['jpg', 'jpeg', 'png', 'bmp','svg'], file.toLowerCase());
                    }
                    if (type === 'video') {
                        return this.endsWithAny(['mp4', 'mkv', 'avi', 'webm'], file.toLowerCase());
                    }
                    //Todo: add other types
                } else {
                    return file.type.indexOf(type) > -1;
                }
            }

            return false;
        },
        endsWithAny: function (suffixes, string) {
            return suffixes.some(function (suffix) {
                return string.endsWith(suffix);
            });
        },
        isJsonValid: function(str) {
            try {
                JSON.parse(str);
            } catch (ex) {
                return false;
            }
            return true;
        },
    }
}
