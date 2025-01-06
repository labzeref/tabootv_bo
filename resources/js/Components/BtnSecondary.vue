<script setup>


</script>

<template>
    <v-btn
        class="px-[20px]  py-[3px] btn-banner bg-primaryDark text-white  "

        rounded="pill"
        size="full">
        <span class="flex items-center justify-center gap-2">
            <slot/>
        </span>
    </v-btn>
</template>

<style scoped>
button {
    box-shadow: 0 4px 23px 0 rgba(170, 4, 20, 0.65);
}

@media (max-width: 767px) {
    .v-btn {
        min-height: 35px;
        width: 100%;
        border-radius: 8px !important;
    }

    button {
        box-shadow: 0 2px 15px 0 rgba(170, 4, 20, 0.45) !important;
    }
}
</style>
