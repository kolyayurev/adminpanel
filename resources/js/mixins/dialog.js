export const dialog = {
  props:{
    title: {
      type: String,
      default: '',
  },
  },
  data(){
    return{
      dialogVisibility: false,
    }
  },
  methods: {
    openDialog(){
      this.dialogVisibility = true
    },
    closeDialog(){
      this.dialogVisibility = false
    },
  },
}
