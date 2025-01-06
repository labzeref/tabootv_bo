<script setup>
import {Link} from "@inertiajs/vue3"

defineProps({
    name: String,
    label: String,
    url: {
        type: String,
        required: false
    },
    inputType: {
        type: String,
        default: 'text'
    },
    linkLabel: {
        type: String,
        required: false
    },
    icon: {
        type: Function,
        required: false
    },
    placeholder: {
        type: String,
        required: false
    },
    marginBottom: {
        type: Boolean,
        default: true
    }
})

const model = defineModel({
    type: String,
    required: true,
});
</script>

<template>
    <label :for="name" class="flex flex-col gap-3 text-[14px] w-full max-h-[84px]">
        <span class="font-medium fm-book">{{ label }}</span>

        <span class="flex items-center">
            <input
                :placeholder="placeholder"
                disabled
                :type="inputType"
                :name="name"
                v-model="model"
                class="customInput w-full bg-transparent rounded-l-[12px] px-4 py-3 placeholder-secondary leading-[24px]"
            >
            <Link :href="url"
                  class="customInputLink text-primary min-w-fit rounded-r-[12px] px-4 fm-book">
                {{ linkLabel }}
                <component :is="icon"/>
            </Link>
        </span>
    </label>
</template>

<style scoped>
.customInput {
    border-top: 1px solid rgba(255, 255, 255, 15%);
    border-left: 1px solid rgba(255, 255, 255, 15%);
    border-bottom: 1px solid rgba(255, 255, 255, 15%);
}

.customInputLink {
    font-size: 14px;
    font-weight: 400;
    line-height: 24px !important;
    padding-block: 12px;
    letter-spacing: 0.02em;

    border-top: 1px solid rgba(255, 255, 255, 15%);
    border-right: 1px solid rgba(255, 255, 255, 15%);
    border-bottom: 1px solid rgba(255, 255, 255, 15%);
}

</style>
