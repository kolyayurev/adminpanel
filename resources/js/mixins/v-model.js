export default {
  props: {
      modelValue: {
    //   type: [String, Number, Boolean, Array, Object, Date, Function, Symbol],
      validator: v => true,
      required: true,
    },
  },
  computed: {
    localValue: {
      get() {
        return this.modelValue;
      },
      set(localState) {
        this.$emit('update:modelValue', localState);
      },
    },
  },
};
