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

const recordVideo = ref(true)

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
const searchTimer = ref(null);

const fetchVideos = () => {
    axios.get(route('videos.list', {
        sort_by: sortBy.value,
        category_ids: categoryIds.value,
        country_id: countryId.value,
        search: search.value,

    })).then(response => {
        videos.value = response.data.videos;
        recordVideo.value = response.data.videos[0]
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
        <div class="flex flex-col md:flex-row justify-between items-baseline gap-4 md:mt-[16px]">
            <h5 class="whitespace-nowrap">All Videos</h5>

            <div class="flex flex-col w-full items-end gap-[25px]">
                <div class="flex w-full gap-[15px] items-center justify-end">
                    <div class="md:flex items-center gap-[9px] hidden">
                        <v-btn icon="" height="38" width="38" :rounded="false" class="rounded-lg">
                            <Search @click="fetchVideos"/>
                        </v-btn>
                        <input @keydown="fetchVideos"
                            class="search-input"
                            type="text" placeholder="Search Name, Country, Categories, Titles.."
                            v-model="search"
                        />
                    </div>

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

                    <v-btn tile :color="filterEnabled ? '#ab0013' : ''" :rounded="false" class="rounded-lg px-0"
                           height="38" width="38" minWidth="38" @click="filterEnabled = !filterEnabled">
                        <Filter :active="filterEnabled" />
                    </v-btn>
                </div>
                <div v-if="filterEnabled" class="flex flex-wrap gap-[6px] md:gap-[10px] w-full justify-end">
                    <v-btn height="34" class="rounded-full fs-16 lh-20 fw-400 fm-book "
                           v-for="(category, index) in categories" :key="index"
                           @click="handleCategoryClick(category.id)"
                           :class="categoryIds.includes(parseInt(category.id, 10)) ? 'bg-primaryDark' : ''"
                           >
                        {{ category.name }}
                    </v-btn>
                </div>
            </div>
        </div>

        <div v-if="!recordVideo" class="custom-height-video grid place-items-center">
            <p class="fs-20 text-white fm-medium fw-500 text-center my-[10px] ">No Record Found!</p>
        </div>
        <div class="w-full grid-all-videos  mt-[20px] md:mt-[25px]">
            <div class=" " v-for="(video, index) in videos" :key="index">
                <SecondaryCard
                    :object="video"

                    :customMargin="'mb-0'"
                />
                <!-- :heightCustom="'video-img-h'" -->
            </div>
        </div>
    </Page>
</template>

<style scoped>
.custom-height-video{
    height: calc(100vh - 760px);
}
</style>
