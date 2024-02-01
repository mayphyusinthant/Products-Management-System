<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import SectionBorder from '@/Components/SectionBorder.vue';
    import Welcome from '@/Components/Welcome.vue';
    import { Link, Head } from '@inertiajs/vue3';
    import { defineProps , watch } from 'vue';
    import { reactive, ref, onMounted, watchEffect} from 'vue';
    import JetLabel from "@/Components/InputLabel.vue";
    import JetInput from "@/Components/TextInput.vue";
    import axios from 'axios';
    import { useForm } from '@inertiajs/vue3';

    //const { items, artTypes,  filteredType } = defineProps(['items', 'artTypes',  'filteredType']);

    const items = ref([]);  
    const artTypes = ref([]);
    const filteredType = ref(null);
    const loading = ref(true);
    let searchTimeout;
    import { getCookie } from '@/utils'; 

    let darkMode = null;

    onMounted(() => {
      if(getCookie('darkMode') == 'on'){
        darkMode = true;
      }else{
        darkMode = false;
      }

      console.log(darkMode)
    });

    const components = {
    JetLabel,
    JetInput
  };

  async function filterData() {
  try {
    loading.value = true;

    // Fetch parameter value - art Type
    const par1 = document.getElementsByName('artTypeParam')[0];
    const index = par1.selectedIndex;
    const artType = par1.options[index].text;
    const url = `/api/items/filter?artType=${encodeURIComponent(artType)}`;
    const response = await axios.get(url);
    items.value = response.data.items;
    artTypes.value = response.data.artTypes;
    filteredType.value = response.data.filteredType;
    console.log('Fetched Filtered API Data...', url );

  } catch (error) {
    console.error('Error fetching filtered data:', error);
  }
  finally {
        loading.value = false;
      }
}

  // This search function includes debouncing in order to reduce unnecessary request sent to server per keystoke
  // While maintaining the real-time search functionality 
  async function search() {
    // Clear the previous timeout
    clearTimeout(searchTimeout);

  // Set a time to wait for 500 milliseconds before triggering the search
  searchTimeout = setTimeout(async () => {
    try{
      loading.value = true;

      const query = document.getElementsByName('searchQuery')[0].value;
      const url = `/api/items/search?itemName=${encodeURIComponent(query)}`;
      const response = await axios.get(url);
      items.value = response.data.items;
      artTypes.value = response.data.artTypes;
      console.log("Fetched Search Query from ", url, ". Query:" , query);
    }
    catch (error) {
      console.error('Error fetching filtered data:', error);
    }
    finally {
        loading.value = false;
      }
  }, 300)
}


  onMounted(async () => {
    if (getCookie('darkMode') == 'on') {
      darkMode = true;
    } else {
      darkMode = false;
    }
    // Fetch data from the API
    try {
      const response = await axios.get('/api/items');
      
      // Check if the response is empty or api is not rendered yet.
      if (!response.data.items || !response.data.artTypes || !response.data.filteredType) {
        console.log('Loading API Data...');

        // Make a separate call to a backend route to trigger data creation or processing
        await axios.get('/api/items');

        // After backend processing, make the original API call again to fetch the data
        const updatedResponse = await axios.get('/api/items');
        items.value = updatedResponse.data.items;
        artTypes.value = updatedResponse.data.artTypes;
        filteredType.value = updatedResponse.data.filteredType;
      } else {
        // Data exists, set the values as usual
        items.value = response.data.items;
        artTypes.value = response.data.artTypes;
        filteredType.value = response.data.filteredType;
      }
    } 
    catch (error) {
      console.error('Error fetching data:', error);
    }
    finally {
        loading.value = false;
      }
  });

  async function paginate(url) {
  if (url) {
    try {
      loading.value = true;
      //Pagination for Filtered Data
      const filtered_query = document.getElementsByName('artTypeParam')[0];
      //Pagination for Filtered Data
      const search_query = document.getElementsByName('searchQuery')[0].value;

      if(filtered_query){
        const index = filtered_query.selectedIndex;
        const artType = filtered_query.options[index].text;

        var indexOfQuestionMark = url.indexOf('?');

        // Split the URL into two parts
        var baseUrl = url.substring(0, indexOfQuestionMark);
        var queryParams = url.substring(indexOfQuestionMark + 1);
        url = `${baseUrl}?artType=${encodeURIComponent(artType)}&&${queryParams}`;

        console.log('Base URL:', baseUrl);
        console.log('Query Parameters:', queryParams);
        console.log(url);
      }
      
      if(search_query){
        var indexOfQuestionMark = url.indexOf('?');

        // Split the URL into two parts
        var baseUrl = url.substring(0, indexOfQuestionMark);
        var queryParams = url.substring(indexOfQuestionMark + 1);
        url = `${baseUrl}?itemName=${encodeURIComponent(search_query)}&&${queryParams}`;

        console.log('Base URL:', baseUrl);
        console.log('Query Parameters:', queryParams);
        console.log(url);
      }

      // Make an API call to fetch data from the specified page
      const response = await axios.get(url);

      // Check if the response is empty or incomplete
      if (!response.data.items || !response.data.artTypes || !response.data.filteredType) {
        console.log('Loading API Data (Pagination)...');

        // Make a separate call to a backend route to trigger data creation or processing
        await axios.get(url);

        // After backend processing, make the original API call again to fetch the data
        const updatedResponse = await axios.get(url);
        items.value = updatedResponse.data.items;
        artTypes.value = updatedResponse.data.artTypes;
        filteredType.value = updatedResponse.data.filteredType;
      } else {
        // Data exists, set the values as usual
        items.value = response.data.items;
        artTypes.value = response.data.artTypes;
        filteredType.value = response.data.filteredType;
      }

      console.log('Fetched API Data from Page (Pagination):', url);

    } catch (error) {
      console.error('Error fetching paginated data:', error);
    }
    finally {
        loading.value = false;
        console.log(loading.value);
    }
  }
}

</script>

<!-- resources/js/Pages/Items/Index.vue -->
<template #header>
    <AppLayout title="Item" :class="{ 'dark-mode-class': darkMode }">
        <template #header >
          <div class="grid grid-cols-4 items-center">
            <!-- Item Label -->
            <h2 class="col-span-1 md:grid-cols-2 lg:grid-cols-4 font-semibold custom-text-xl text-pink-800 leading-tight" :class="{ 'dark:text-gray-800': darkMode }">
                Item
            </h2>
            
            <form @submit.prevent="search" class="col-span-1 flex items-center space-x-2 mr-20 sm:flex-col sm:items-start">
              <JetInput name="searchQuery" placeholder="Search By Name"
                @keyup.enter="search" 
                @input="search"> </JetInput>
            </form>


            <form @submit.prevent="filterData" class="col-span-1 flex items-center space-x-2">
                <select name="artTypeParam"
                        id="artType"
                        class="form-select w-full border-gray-300 focus:border-pink-400 focus:ring-pink-400 rounded-md shadow-sm"
                        :class="{ 'dark:bg-gray-800': darkMode, 'focus:border-none': darkMode, 'focus:ring-white': darkMode, 'dark:text-white': darkMode }"
                >

                    <option value="filteredType"  selected>{{ filteredType ? filteredType : "Filter By Art Type" }}</option>
                    <option v-for="artType in artTypes" :key="artType" :value="artType">{{ artType }}</option>
                </select>
        
            
                <span :class="{ 'dark:bg-gray-900': darkMode }" @click="filterData()"
                      class="bg-pink-900 text-white py-2 px-4 rounded hover:bg-pink-950 focus:shadow-outline focus:outline-none">
                    Filter
                </span>
            </form>
          
           
            <!-- Create Button -->
            <div class="col-span-1 w-full flex justify-end">
                <button class="bg-pink-900 text-white py-2 px-4 rounded hover:bg-pink-950 focus:shadow-outline focus:outline-none"
                        :class="{ 'dark:bg-gray-700': darkMode }">
                    <Link :href="route('items.create')">+ Create</Link>
                </button>
            </div>
        </div>
</template>                  


        <div v-if="loading" class="flex items-center justify-center h-screen">
          <div role="status">
            <svg aria-hidden="true" class="inline w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-pink-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
            </svg>
            <span class="sr-only">Loading...</span>
        </div>
        </div>

        
        <div v-else class="py-3" >

          <!-- Pagination controls -->
         <nav aria-label="Page navigation"  class = "flex items-center justify-center ">
          <ul class="pagination flex">
    
            <li v-for="link in items.links" :key="link.label" class="page-item" :class="{ 'active': link.active, 'disabled': !link.url }">
              <button v-if="link.url" @click="paginate(link.url)"
              :class="{ 'bg-red-900': link.active,  'border-glow': link.active , 'bg-pink-900': !link.active, }"
              class="px-4 py-2 rounded mx-2 my-2 mb-0 text-white">
              <span v-html="link.label"></span>

            </button>
          </li>
          </ul>
        </nav><br/>

            <div class="max-w-7xl mx-auto p-5 sm:px-6 lg:px-8 bg-white overflow-hidden shadow-xl sm:rounded-lg" :class="{ 'dark:bg-gray-800': darkMode }">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 ">
                  <!-- Loop through items and create a card for each -->
                  <div v-for="item in items.data" :key="item.id" class="p-0 bg-pink-950  overflow-hidden shadow-xl sm:rounded-lg text-white text-center" 
                  :class="{ 'dark:bg-gray-700': darkMode}">
                  <!-- <div v-if="item.owner === $page.props.auth.user.id"> -->

                    <div class="w-full md:w-auto flex md:flex justify-end "  >
                      <button v-if="item.status === 'Available'" 
                              class="bg-pink-200 text-pink-950 px-10 py-2 transform rotate-45  translate-y-4 right-[-80%] translate-x-9   m-0">{{ item.status }}</button>
                      <button v-else-if="item.status === 'Sold Out'" 
                              class="bg-red-500 text-white px-10 py-2 transform rotate-45  translate-y-4 right-[-80%] translate-x-9   m-0">{{ item.status }}</button>
                      <button v-else-if="item.status == 'Not For Sale'" 
                      class="bg-red-500 text-white px-10 py-2 transform rotate-45  translate-y-4 right-[-80%] translate-x-9   m-0">{{ item.status }}</button>
                    </div>
  
                    <div class = "p-5"  >
  
                      <b>{{ item.name }}</b>
                    
                      <img :src="`http://127.0.0.1:8000/${item.img}`" class="w-full h-auto object-cover mb-4" alt="Item Image">
                      <p class = "text-justify">{{ item.description }}</p>
                      <hr/>
                      <b>{{ item.artType }}</b>
                      <p>{{ item.size }} - {{ item.medium }}</p>
                      <p>{{ item.price }} MMK</p>
                      <p> Owned By <b> {{ item.user.name }} </b></p>

                      <button class="bg-pink-200 text-pink-950 px-2 py-2 m-1 shadow-xl sm:rounded-lg"> 
                        <Link :href="route('items.view',  { id: item.id })"  >
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                          </svg>
                          
                        </Link>               
                      </button>
                      <button class="bg-pink-200 text-pink-950 px-2 py-2 m-1 shadow-xl sm:rounded-lg"> 
                        <Link :href="route('items.edit',  { id: item.id })" :data="{ edit: true }" >
                          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                            <path d="m2.695 14.762-1.262 3.155a.5.5 0 0 0 .65.65l3.155-1.262a4 4 0 0 0 1.343-.886L17.5 5.501a2.121 2.121 0 0 0-3-3L3.58 13.419a4 4 0 0 0-.885 1.343Z" />
                          </svg>
                        </Link>               
                      </button>
                      <button class="bg-pink-200 text-pink-950 px-2 py-2 m-1 shadow-xl sm:rounded-lg"> 
                        <Link :href="route('items.delete',  { id: item.id })" >
                          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                            <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 0 0 6 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 1 0 .23 1.482l.149-.022.841 10.518A2.75 2.75 0 0 0 7.596 19h4.807a2.75 2.75 0 0 0 2.742-2.53l.841-10.52.149.023a.75.75 0 0 0 .23-1.482A41.03 41.03 0 0 0 14 4.193V3.75A2.75 2.75 0 0 0 11.25 1h-2.5ZM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4ZM8.58 7.72a.75.75 0 0 0-1.5.06l.3 7.5a.75.75 0 1 0 1.5-.06l-.3-7.5Zm4.34.06a.75.75 0 1 0-1.5-.06l-.3 7.5a.75.75 0 1 0 1.5.06l.3-7.5Z" clip-rule="evenodd" />
                          </svg>
                        </Link>
                      </button>
                    </div>
                    <!-- </div> -->
                  </div> 
              </div>
            </div>
          </div>
        
         
    </AppLayout>
</template>


<style>
  .border-glow {
    box-shadow: 0 0 20px rgba(81, 4, 4, 0.9); 
  }
</style>