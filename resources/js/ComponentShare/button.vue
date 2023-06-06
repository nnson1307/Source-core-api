<script>
export default {
  props: {
    type: String,
    name: String,
    className: String
  },
}
</script>

<template>
    <button :type="type" :class="className">{{ name }}</button>
</template>
