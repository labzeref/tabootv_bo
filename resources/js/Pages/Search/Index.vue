<script setup>
import { defineAsyncComponent, ref, onMounted } from "vue";
import axios from "axios";
import { usePage } from "@inertiajs/vue3";
import { Head } from "@inertiajs/vue3";

const Page = defineAsyncComponent(() => import("@/Components/Page.vue"));
const Search = defineAsyncComponent(() => import("@/Components/svg/icons/search.vue"));
const VuetifyDropdown = defineAsyncComponent(() => import("@/Components/VuetifyDropdown.vue"));
const SecondaryCard = defineAsyncComponent(() => import("@/Components/SeriesSecondaryCard.vue"));
const Filter = defineAsyncComponent(() => import("@/Components/svg/icons/filter.vue"));

const props = defineProps({
    categories: Array,
    countries: Array,
})

const sortingOptions = [
    {
        name: 'Newest',
        value: 'newest',
    },
    {
        name: 'Trending',
        value: 'trending',
    },
    {
        name: 'Oldest',
        value: 'old',
    },
];
const filterEnabled = ref(false);
const countryId = ref('');
const categoryIds = ref([]);
const search = ref('');
const sortBy = ref(sortingOptions[0].value);
const videos = ref([]);
const series = ref([]);
const searchTimer = ref(null);

const fetchVideos = () => {
    axios.get(route('videos.search.list', {
        sort_by: sortBy.value,
        category_ids: categoryIds.value,
        country_id: countryId.value,
        search: search.value,

    })).then(response => {
        videos.value = response.data.videos;
        series.value = response.data.series;
    }).catch(error => {
        console.error('Error fetching videos data:', error);
    });
}

const handleCategoryClick = (categoryId) => {
    if (categoryIds.value.includes(categoryId)) {
        categoryIds.value = categoryIds.value.filter(id => id !== categoryId);
    } else {
        categoryIds.value.push(categoryId);
    }

    fetchVideos();
};

const handleCountryChange = (value) => {
    countryId.value = value;
    fetchVideos();
}

const handleSortByChange = (value) => {
    sortBy.value = value
    fetchVideos();
}

onMounted(() => {
    fetchVideos();
});
</script>

<template>
    <Head title="Videos" />

    <Page>

        <div class=" max-w-[1070px] mx-auto mb-6 flex items-center gap-2 mt-3" >
            <v-btn :icon="Search" rounded="lg" @click="fetchVideos" width="38px" height="38px" class="searchBtn"/>
            <input
                class="w-full h-[44px] max-w-[1023px] py-3 px-4 rounded-[10px] text-secondary placeholder-secondary bg-[#4742424D] focus:ring-0 fm-book fw-400 fs-16 leading-[normal]"
                type="text" placeholder="Search videos.."
                v-model="search"
            />
        </div>
        <div class="flex flex-col md:flex-row justify-between items-baseline gap-4">
            <h5 class="">Recommended</h5>

            <div class="flex flex-col w-full items-end gap-[25px]">
                <div class="flex w-full gap-[15px] items-center justify-end">


                    <VuetifyDropdown
                        :list="countries"
                        listName="iso"
                        listValue="id"
                        label="Country"
                        placeholder="USA"
                        @select="handleCountryChange" />

                    <VuetifyDropdown
                        :list="sortingOptions"
                        listName="name"
                        listValue="value"
                        label="Sort by"
                        placeholder="Newest"
                        @select="handleSortByChange"
                    />

                    <v-btn tile :color="filterEnabled ? 'bg-[#AB0013]' : ''" :rounded="false" class="rounded-lg" style="width: 38px;"
                           height="38" @click="filterEnabled = !filterEnabled">
                        <Filter :active="filterEnabled" />
                    </v-btn>
                </div>
                <div v-if="filterEnabled" class="flex flex-wrap gap-[6px] md:gap-[10px] w-full justify-end">
                    <v-btn height="34" rounded="pill" class="text-[12px] md:text-[16px]" style="box-shadow: none !important"
                           v-for="(category, index) in categories" :key="index"
                           @click="handleCategoryClick(category.id)"
                           :class="categoryIds.includes(parseInt(category.id, 10)) ? 'bg-primaryDark' : ''"
                    >
                        {{ category.name }}
                    </v-btn>
                </div>
            </div>
        </div>
        <!-- <div class=" max-w-[1070px] mx-auto mb-6 flex items-center gap-2 mt-3" >
            <v-btn :icon="Search" rounded="lg" @click="fetchVideos" width="38px" height="38px" class="searchBtn"/>
            <input
                class="w-full h-[44px] max-w-[1023px] py-3 px-4 rounded-[10px] text-secondary placeholder-secondary bg-[#4742424D] focus:ring-0 fm-book fw-400 fs-16 leading-[normal]"
                type="text" placeholder="Search videos.." @keydown.enter="fetchVideos"
                v-model="search"
            />
        </div> -->


        <div class="w-full grid grid-cols-1  md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 2xl:grid-cols-4 gap-search mt-[20px] md:mt-[25px]">
            <div class=" " v-for="(video, index) in videos" :key="index">
                <SecondaryCard :object="video"

                    :customMargin="'mb-0'"
                />
                <!-- :heightCustom="'video-img-h'" -->
            </div>
            <div class="relative" v-for="(video, index) in series" :key="index">
                <SecondaryCard :object="video"
                               :customMargin="'margin-bottom'"
                />
            </div>
        </div>


    </Page>
</template>
