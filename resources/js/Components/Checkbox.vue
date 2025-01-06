<script setup>
defineProps({
    name: String,
    id: String,
})

const model = defineModel({
    type: Boolean,
    required: true,
});
</script>

<template>
    <input type="checkbox"
           name="remember-me"
           id="remember-me"
           v-model="model"
           class="bg-transparent focus:ring-0 focus: rounded">
</template>

<style scoped>
input {
    border: 1px solid #D5D8E2
}

input:checked {
    border: 1px solid #D5D8E2;
}

input:hover:checked {
    border: 1px solid #D5D8E2
}
</style>
