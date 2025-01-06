<script setup>
import Tick from "@/Components/svg/icons/tick.vue";
import {defineEmits} from 'vue'

const props = defineProps({
  video: Object
})

const emit = defineEmits(['toggleSelection'])

function toggleSelection(event) {
  emit('toggleSelection', { id: props.video.id, checked: event.target.checked })
}
</script>

<template>
  <div
       class="Gradiant border p-[5px] flex gap-3 hover:opacity-85 cursor-pointer duration-300 relative z-20 bg-dark">
    <div class="w-full relative overflow-hidden rounded-[15px]">
      <img :src="video.img" alt="placeholder Image" class="w-full rounded-[6px]">
      <div
          class="top-[40%] absolute -inset-0 -bottom-1 bg-gradient-to-t from-dark to-dark/0"></div>
      <div class="absolute left-2 top-2">
        <input @change="toggleSelection" class="rounded-full size-6 focus:ring-0 focus:outline-none focus:border-none"
            type="checkbox">
      </div>
    </div>

    <div class="w-full flex flex-col justify-around">
      <h3 class="leading-5">{{ video.title }}</h3>
      <p class="text-sm text-secondary mb-[3px] ">{{ video.posted_at }}</p>
      <p class="text-sm text-[#9F9F9F] flex items-center gap-1">{{ video.channel }}
        <tick/>
      </p>
    </div>
  </div>
</template>

<style scoped>
.Gradiant {
  background: linear-gradient(90deg, rgba(110, 2, 6, 0.77) 0%, rgba(55, 55, 55, 0.2) 120%);
  border-radius: 12px;
}

input[type="checkbox"] {
  outline: none;
  border: 1px solid white;
  cursor: pointer;
  width: 20px;
  height: 20px;
  background-color: transparent;
  position: relative;
}

input[type="checkbox"]:checked {
  border: none;
  background-color: #0EDA53;
}

input[type="checkbox"]:checked::after {
  content: "";
  position: absolute;
  top: 50%;
  left: 50%;
  width: 12px;
  height: 7px;
  border: solid black;
  border-width: 0 3px 3px 0;
  border-radius: 2px;
  transform: translate(-50%, -65%) rotate(-45deg) scaleX(-1) ;
}

input[type="checkbox"] {
  transition: background-color 0.3s ease;
}

</style>
