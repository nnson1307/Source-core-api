<script>
export default {
  props: {
    name: String,
  },
}
</script>

<template>
    <label v-if="name">{{ name }}</label>
</template>
