<script setup>

import Share from "@/Components/svg/icons/share.vue";
</script>

<template>
    <v-btn class="fm-book button" rounded="pill"  >
                                <span class="flex gap-1 md:gap-3 items-center px-1 actionButtons">
                                <share/>
                                Share
                                </span>
    </v-btn>
</template>

<style scoped>
.actionButtons {
    font-weight: 400 !important;
    font-size: 16px !important;
    line-height: 20px;
}

.button{
    max-width: 101px !important;
}

@media (max-width: 768px) {
    .button{
        max-width: 82px !important;
        height: 28px !important;
    }
    .actionButtons{
        max-width: 79px !important;
        max-height: 28px !important;
    }
    .actionButtons {
        font-weight: 400 !important;
        font-size: 14px !important;
        line-height: 12px;
    }
}

</style>
