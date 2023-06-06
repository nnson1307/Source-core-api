<script>
export default {
  props: {
    type: String,
    id: String,
    placeholder: String,
    modelValue: String,
  },
}
</script>

<template>
    <input :type="type" class="form-control" :id="id" :placeholder="placeholder"
        @input="$emit('update:modelValue', $event.target.value)" :value="modelValue">
</template>
