<script setup>
import {Link} from "@inertiajs/vue3"
import WhiteArrowLeft from "@/Components/svg/icons/whiteArrowLeft.vue";

const props = defineProps({
    title: String,
    btn: String,
})

</script>

<template>
    <div class="min-h-[40vw] grid place-items-center p-1 py-8">
        <form @submit.prevent="$emit('submit')"
              class="max-w-[659px] w-full rounded-[20px] md:bg-[#393535A1] md:border border-white/10 py-[50px] md:py-[107px] md:px-[87px] flex flex-col">
            <h2 class="flex items-center gap-1 h2-2xsmall">
                <Link :href="route('profile.show')"><white-arrow-left class="-scale-x-100"/></Link>
                {{ title }}
            </h2>

            <div class="body">
                <slot />
            </div>

            <v-btn type="submit" rounded="pill" color="primaryDark" width="257" height="48"
                   style="font-weight: 700; letter-spacing: 0">{{ btn }}
            </v-btn>

        </form>

    </div>

</template>

<style scoped>
.body{
    padding-top: 25px;
    padding-bottom: 45px;
}

@media (max-width: 768px) {
   .body{
       padding-block: 20px !important;
   }
}


h2 {
    font-size: 24px;
    font-weight: 500;
    line-height: 36px;
}
</style>
