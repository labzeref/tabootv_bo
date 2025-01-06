<script setup>
import {defineAsyncComponent, onMounted, ref} from "vue";
import {usePage} from "@inertiajs/vue3";
import {Head} from "@inertiajs/vue3";
import axios from "axios";
import {collect} from "collect.js";

const Page = defineAsyncComponent(() => import("@/Components/Page.vue"));
const VuetifyDropdown = defineAsyncComponent(() => import("@/Components/VuetifyDropdown.vue"));
const SecondaryCard = defineAsyncComponent(() => import("@/Components/SeriesSecondaryCard.vue"));

const props = defineProps({
    categories: Array,
})


const sortingOptions = [
    {
        name: 'Newest',
        value: 'newest',
    },
    // {
    //     name: 'Trending',
    //     value: 'trending',
    // },
    {
        name: 'Oldest',
        value: 'old',
    },
];
const filterEnabled = ref(false);
const seriesByChannel = ref([]);

const categoryIds = ref([]);
const sortBy = ref(sortingOptions[0].value);

const fetchSeries = () => {
    axios.get(route('series.list', {
        sort_by: sortBy.value,
        category_ids: categoryIds.value,
    })).then((response) => {
        seriesByChannel.value = collect(response.data.series).groupBy((item) => item.channel.name).all();
    }).catch((error) => {
        console.error('Error fetching series data:', error);
    });
}

const handleSortingChange = (value) => {
    sortBy.value = value;

    fetchSeries();
}


onMounted(() => {
    fetchSeries();
});

const handleCategoryClick = (categoryId) => {
    if (categoryIds.value.includes(categoryId)) {
        categoryIds.value = categoryIds.value.filter((id) => id !== categoryId);
    } else {
        categoryIds.value.push(categoryId);
    }
    fetchSeries();
};


</script>

<template>
    <Head title="Series"/>

    <Page class="">
        <!-- <div class="flex justify-between items-center flex-wrap gap-3">
                    <div class="flex gap-[15px] items-center w-fit justify-end ml-auto">
                        <VuetifyDropdown
                            :list="sortingOptions"
                            listValue="value"
                            listName="name"
                            label="Sort by"
                            placeholder="Newest Post"
                            @select="handleSortingChange"
                        />

                        <v-btn icon tile :class="filterEnabled && 'bg-primaryDark'" :rounded="false" class=" rounded-lg"
                            width="38px"
                            height="38"
                            @click="() => filterEnabled = !filterEnabled">
                            <svg width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M12.9999 9.91252V15C12.9999 15.3788 12.7859 15.725 12.4472 15.8944L8.44716 17.8944C7.78226 18.2269 6.99994 17.7434 6.99994 17V9.91252L0.225986 1.63324C-0.30823 0.980307 0.156317 0 0.999943 0H18.9999C19.8436 0 20.3081 0.980307 19.7739 1.63324L12.9999 9.91252ZM10.9999 14.382V9.55556C10.9999 9.32471 11.0798 9.10098 11.226 8.92232L16.8897 2H3.11019L8.7739 8.92232C8.92008 9.10098 8.99994 9.32471 8.99994 9.55556V15.382L10.9999 14.382Z"
                                    :class="filterEnabled ? 'fill-white' : 'fill-[#B5B5BE]'"/>
                            </svg>
                        </v-btn>
                    </div>
                    <v-slide-y-transition v-show="filterEnabled">
                        <div class="flex flex-wrap gap-[6px] md:gap-[10px] w-full justify-end">
                            <v-btn
                                height="34"
                                class="rounded-full text-[12px] md:text-[16px]"
                                v-for="(category, index) in categories"
                                :key="index"
                                @click="handleCategoryClick(category.id)"
                                :class="categoryIds.includes(parseInt(category.id, 10)) ? 'bg-primaryDark' : ''"
                            >
                                {{ category.name }}
                            </v-btn>
                        </div>
                    </v-slide-y-transition>
        </div> -->


        <div
            v-for="(channel, channelName, key) in seriesByChannel"
            :key
        >
            <!-- <h5 class=" ">Series By: <span class="text-primary font-extrabold">{{ channelName }}</span></h5> -->
            <div class="flex justify-between items-center flex-wrap gap-3 mb-6">
                <h5 class=" ">Series By: <span class="text-primary font-extrabold">{{ channelName }}</span></h5>
                <!-- <div class="flex flex-col sm:flex-row justify-end items-center gap-4"> -->
                    <!-- <div class="flex flex-col items-end gap-[25px] w-full md:w-fit"> -->
                        <div v-if="key === 0" class="flex gap-[15px] items-center w-fit justify-end ml-auto">
                            <VuetifyDropdown
                                :list="sortingOptions"
                                listValue="value"
                                listName="name"
                                label="Sort by"
                                placeholder="Newest"
                                @select="handleSortingChange"
                            />

                            <v-btn icon tile :class="filterEnabled && 'bg-primaryDark'" :rounded="false" class=" rounded-lg"
                                width="38px"
                                height="38"
                                @click="() => filterEnabled = !filterEnabled">
                                <svg width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M12.9999 9.91252V15C12.9999 15.3788 12.7859 15.725 12.4472 15.8944L8.44716 17.8944C7.78226 18.2269 6.99994 17.7434 6.99994 17V9.91252L0.225986 1.63324C-0.30823 0.980307 0.156317 0 0.999943 0H18.9999C19.8436 0 20.3081 0.980307 19.7739 1.63324L12.9999 9.91252ZM10.9999 14.382V9.55556C10.9999 9.32471 11.0798 9.10098 11.226 8.92232L16.8897 2H3.11019L8.7739 8.92232C8.92008 9.10098 8.99994 9.32471 8.99994 9.55556V15.382L10.9999 14.382Z"
                                        :class="filterEnabled ? 'fill-white' : 'fill-[#B5B5BE]'"/>
                                </svg>
                            </v-btn>
                        </div>
                        <v-slide-y-transition v-show="filterEnabled && key === 0">
                            <div class="flex flex-wrap gap-[6px] md:gap-[10px] w-full justify-end">
                                <v-btn
                                    height="34"
                                    rounded="pill"
                                    class="text-[12px] md:text-[16px]"
                                    v-for="(category, index) in categories"
                                    :key="index"
                                    @click="handleCategoryClick(category.id)"
                                    :class="categoryIds.includes(parseInt(category.id, 10)) ? 'bg-primaryDark' : ''"
                                >
                                    {{ category.name }}
                                </v-btn>
                            </div>
                        </v-slide-y-transition>
                    <!-- </div> -->
                <!-- </div> -->
            </div>

            <div
                class="w-full grid-all-videos series-gap mt-3 md:mt-[25px] ">
                <div class=" relative" v-for="(series, key) in channel.items" :key="key">
                    <SecondaryCard :object="series"
                        :heightCustom="'video-img-h'"
                    />
                </div>
            </div>
        </div>
    </Page>
</template>


<style scoped>

</style>
