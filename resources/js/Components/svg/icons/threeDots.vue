<script setup>
defineProps({
    color: {
        type: String,
        default: '#807E81'
    }
})
</script>
<template>
	<svg xmlns="http://www.w3.org/2000/svg" width="25px" height="2em" viewBox="0 0 16 16">
		<path :fill="color" d="M3 9.5a1.5 1.5 0 1 1 0-3a1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3a1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3a1.5 1.5 0 0 1 0 3" />
	</svg>
    <!-- <svg xmlns="http://www.w3.org/2000/svg" width="25" height="6" viewBox="0 0 25 6" :fill="color">
        <circle cx="2.5" cy="3" r="2.5" fill="#807E81"/>
        <circle cx="12.5" cy="3" r="2.5" fill="#807E81"/>
        <circle cx="22.5" cy="3" r="2.5" fill="#807E81"/>
      </svg> -->
</template>
