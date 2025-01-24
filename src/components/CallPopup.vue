<template>
  <div class="call-box bg-gray-900 text-white p-4 rounded-lg shadow-lg fixed bottom-5 right-5">
    <div class="flex justify-between items-center">
      <span class="font-semibold">{{ username }}</span>
      <div class="flex gap-2">
        <button @click="toggleMute" class="bg-gray-600 p-2 rounded-full">
          <svg v-if="isMuted" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M9 9v6h4l5 5V4l-5 5H9z"/></svg>
          <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M3 9v6h4l5 5V4L7 9H3z"/></svg>
        </button>
        <button @click="$emit('endCall')" class="bg-red-600 p-2 rounded-full">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M3 10h18v4H3v-4z"/></svg>
        </button>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  props: {
    username: String,
  },
  data() {
    return {
      isMuted: false,
    };
  },
  methods: {
    toggleMute() {
      this.isMuted = !this.isMuted;
    },
  },
};
</script>

<style scoped>
.call-box {
  width: 200px;
  height: 100px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  position: absolute;
}
</style>
