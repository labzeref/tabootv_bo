<script setup>
import {defineAsyncComponent, ref} from 'vue';

const DropdownArrow = defineAsyncComponent(() => import('@/Components/svg/icons/dropdownArrow.vue'));

const emit = defineEmits(['select'])

const props = defineProps({
    label : String,
    list : Array,
    listValue: String,
    listName: String,
    placeholder: String
})

const selectedItem = ref(props.placeholder);

const selectItem = (item) => {
    selectedItem.value = getName(item);

    emit('select', getValue(item));
};

const getValue = (item) => {
    return props.listValue ? item[props.listValue] : item;
};

const getName = (item) => {
    return props.listName ? item[props.listName] : item;
};
</script>

<template>
    <div class="flex items-center w-full max-w-[330px] md:max-w-fit">
        <div
            class="flex gap-1 bg-[#4742424D] w-full pl-3 pr-2.5 items-center rounded-l-lg relative cursor-default min-h-[38px] min-w-fit">
            <hr class="absolute border h-full border-gray-400 right-0 top-0 bottom-0">
            <span class="fs-14 text-secondary lh-14 fm-book fw-400">{{ props.label }} :</span>
            <span
                class="bg-transparent rounded-l-lg focus:outline-none focus:ring-0 text-dark-dc fs-14 fm-medium lh-14 fw-500">{{
                    selectedItem
                }}</span>
        </div>

        <v-btn icon color="#4742424D" style="border-radius: 0 10px 10px 0; width: auto; height: 38px" class="px-2 text-[12px]">
            <DropdownArrow color="rgba(128, 126, 129, 1)"/>

            <v-menu activator="parent">
                <v-list>
                    <v-list-item v-for="(item, key) in list" :key @click="selectItem(item)">
                        <v-list-item-title>{{ getName(item) }}</v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-menu>
        </v-btn>
    </div>
</template>

<style scoped>

</style>
