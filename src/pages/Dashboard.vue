<template>
  <div class="dashboard-container">
    <h2 class="text-2xl font-bold mb-4">User Calls</h2>
    <table class="min-w-full bg-white">
      <thead>
        <tr>
          <th class="py-2 px-4 border-b">Call ID</th>
          <th class="py-2 px-4 border-b">Date</th>
          <th class="py-2 px-4 border-b">Duration</th>
          <th class="py-2 px-4 border-b">Status</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="call in calls" :key="call.id">
          <td class="py-2 px-4 border-b">{{ call.id }}</td>
          <td class="py-2 px-4 border-b">{{ formatDate(call.created_at) }}</td>
          <td class="py-2 px-4 border-b">{{ convertDuration(call.duration) }}</td>
          <td class="py-2 px-4 border-b">{{ call.status }}</td>
        </tr>
      </tbody>
    </table>

    <div class="pagination mt-4">
      <button 
        @click="changePage(-1)" 
        :disabled="!prevPageUrl" 
        class="px-4 py-2 bg-gray-300 rounded mr-2 disabled:opacity-50">
        Previous
      </button>
      <button 
        @click="changePage(1)" 
        :disabled="!nextPageUrl" 
        class="px-4 py-2 bg-gray-300 rounded disabled:opacity-50">
        Next
      </button>
    </div>
  </div>
</template>

<script>
import { get } from "../http/auth-api";
import { format } from "date-fns";
import Sidebar from "../components/Sidebar.vue";

export default {
  components: {
    Sidebar,
  },

  data() {
    return {
      calls: [],
      currentPage: 1,
      totalPages: 1,
      nextPageUrl: null,
      prevPageUrl: null,
      cache: {},
    };
  },

  async created() {
    await this.fetchCalls();
  },

  methods: {
    async fetchCalls() {
      if (this.cache[this.currentPage]) {
        this.setCallData(this.cache[this.currentPage]);
      } else {
        try {
          const response = await get(`/user/calls?page=${this.currentPage}&perPage=2`);
          const { data, last_page, next_page_url, prev_page_url } = response.data;

          const pageData = {
            calls: data,
            totalPages: last_page,
            nextPageUrl: next_page_url,
            prevPageUrl: prev_page_url,
          };

          this.setCallData(pageData);
          this.cache[this.currentPage] = pageData;
        } catch (err) {
          console.error(`Error fetching calls for page ${this.currentPage}:`, err);
        }
      }
    },

    setCallData({ calls, totalPages, nextPageUrl, prevPageUrl }) {
      this.calls = calls;
      this.totalPages = totalPages;
      this.nextPageUrl = nextPageUrl;
      this.prevPageUrl = prevPageUrl;
    },

    async changePage(direction) {
      const newPage = this.currentPage + direction;
      if ((direction === 1 && this.nextPageUrl) || (direction === -1 && this.prevPageUrl)) {
        this.currentPage = newPage;
        await this.fetchCalls();
      }
    },

    formatDate(date) {
      return format(new Date(date), "dd/MM/yyyy HH:mm:ss");
    },

    convertDuration(duration) {
      const hours = Math.floor(duration / 3600);
      const minutes = Math.floor((duration % 3600) / 60);
      return `${hours}h ${minutes}m`;
    },
  },
};
</script>

<style scoped>
.dashboard-container {
  max-width: 800px;
  margin: 50px auto;
  padding: 20px;
  border: 1px solid #ccc;
  border-radius: 5px;
  background-color: #f9f9f9;
}

.pagination button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
</style>
