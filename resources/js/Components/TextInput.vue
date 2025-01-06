<script setup>

defineProps({
    name: String,
    label: String,
    inputType: {
        type: String,
        default: 'string',
    },
    placeholder: String,
    required: {
        type: Boolean,
        default: true
    },
    errorMessage: String,
})

const model = defineModel({
    type: String,
    required: true,
});
</script>

<template>
    <label :for="name" class="flex flex-col gap-[12px] text-[14px] w-full">
        <span class="font-medium leading-[24px] fm-book">{{ label }} <span v-if="required" class="text-[#FF0000]">*</span></span>
        <v-text-field
            variant="plain"
            density="comfortable"
            hide-details="auto"
            :placeholder="placeholder"
            v-model="model"
            :name="name"
            :type="inputType"
            :errorMessages="errorMessage"
        />
    </label>
</template>
