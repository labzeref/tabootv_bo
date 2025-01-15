<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { defineAsyncComponent, onMounted, reactive, ref } from "vue";
import CommunitySidebar from "./Partials/CommunitySidebar.vue";
import CreatorCard from "@/Pages/Community/CreatorCard.vue";

const Page = defineAsyncComponent(() => import("@/Components/Page.vue"));
const creators = ref([])

onMounted(() => {
    axios.get(route('creators.index')).then(response => {
        if (response.data.creators){
            creators.value = response.data.creators.data
        }
    })
        .catch(error => {
            console.error('Error fetching creators:', error); // Handle the error properly
        });
})

</script>

<template>
  <Head title="Creators" />

  <Page>
    <div class="grid-community">
      <CommunitySidebar />
      <div class="overflow-auto">
        <div class="flex items-center justify-between">
          <h2 class="h2-2xsmall">All Creators</h2>
        </div>
        <div class="grid-creators mt-6">
            <CreatorCard
            v-for="(creator, index) in creators"
            :key="creator.id || index"
            :creator />

        </div>
      </div>
    </div>
  </Page>
</template>
