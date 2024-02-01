<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import SectionBorder from '@/Components/SectionBorder.vue';
    import Welcome from '@/Components/Welcome.vue';
    import { Link, Head } from '@inertiajs/vue3';
    import { defineProps , watch } from 'vue';
    import { reactive, ref, onMounted, watchEffect} from 'vue';
    import JetLabel from "@/Components/InputLabel.vue";
    import ApiTokenManager from '@/Pages/API/Partials/ApiTokenManager.vue';

    import { useForm } from '@inertiajs/vue3';

    //defineProps({ items: Array})
    const { items, artTypes,  filteredType } = defineProps(['items', 'artTypes',  'filteredType']);

    import { getCookie } from '@/utils'; // Adjust the path based on your project structure

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
    JetLabel
  };


  function myfunc() {
    //Fetch parameter value - art Type
    var par1=document.getElementsByName('artTypeParam')[0];
    var index=par1.selectedIndex
    console.log(par1.options[index].text);
    
    var url = '/items/filter?artType=' + encodeURIComponent(par1.options[index].text);
    window.location.href = url;
 
   
  }

</script>

<!-- resources/js/Pages/Items/Index.vue -->
<template #header>
    
    <AppLayout title="Item" :class="{ 'dark-mode-class': darkMode }">
        <template #header >
          <div class="grid grid-cols-4 items-center">
            <!-- Item Label -->
            <h2 class="col-span-1 font-semibold custom-text-xl text-pink-800 leading-tight" :class="{ 'dark:text-white': darkMode }">
                Item
            </h2>
        
            <form @submit.prevent="saveFilter" class="col-span-1 flex items-center space-x-2">
                <select name="artTypeParam"
                        id="artType"
                        class="form-select w-full border-gray-300 focus:border-pink-400 focus:ring-pink-400 rounded-md shadow-sm"
                        :class="{ 'dark:bg-gray-800': darkMode, 'focus:border-none': darkMode, 'focus:ring-white': darkMode, 'dark:text-white': darkMode }"
                >

                    <option value="filteredType"  selected>{{ filteredType ? filteredType : "Filter By Art Type" }}</option>
                    <option v-for="artType in artTypes" :key="artType" :value="artType">{{ artType }}</option>
                </select>
        
              
              


                <span :class="{ 'dark:bg-gray-900': darkMode }" @click="myfunc()"
                      class="bg-pink-900 text-white py-2 px-4 rounded hover:bg-pink-950 focus:shadow-outline focus:outline-none">
                    Filter
                </span>
            </form>
            
           
            <!-- Create Button -->
            <div class="col-span-1 w-full flex justify-end">
                <button class="bg-pink-900 text-white py-2 px-4 rounded hover:bg-pink-950 focus:shadow-outline focus:outline-none"
                        :class="{ 'dark:bg-gray-700': darkMode }">
                    <Link :href="route('items.create')">Create New Item</Link>
                </button>
            </div>
        </div>
        </template>
        <div class="py-12" >
            <div class="max-w-7xl mx-auto p-5 sm:px-6 lg:px-8 bg-white overflow-hidden shadow-xl sm:rounded-lg" :class="{ 'dark:bg-gray-800': darkMode }">
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4 ">



                <!-- Loop through items and create a card for each -->
                <div v-for="item in items" :key="item.id" class="p-0 bg-pink-950  overflow-hidden shadow-xl sm:rounded-lg text-white text-center" 
                :class="{ 'dark:bg-gray-700': darkMode}">
                 
                  <div class="w-full md:w-auto flex md:flex justify-end "  >
                    <button v-if="item.status === 'Available'" 
                            class="bg-pink-200 text-pink-950 px-10 py-2 transform rotate-45  translate-y-4 right-[-80%] translate-x-9   m-0">{{ item.status }}</button>
                    <button v-else-if="item.status === 'Sold Out'" 
                            class="bg-red-500 text-white px-10 py-2 transform rotate-45  translate-y-4 right-[-80%] translate-x-9   m-0">{{ item.status }}</button>
                    <button v-else-if="item.status == 'Not For Sale'" 
                    class="bg-red-500 text-white px-10 py-2 transform rotate-45  translate-y-4 right-[-80%] translate-x-9   m-0">{{ item.status }}</button>
                  </div>

                  <div class = "p-5"  >

                    <b>{{ item.name }}</b><br/>
                  
                    <img :src="`http://127.0.0.1:8000/${item.img}`" class="w-full h-auto object-cover mb-4" alt="Item Image">
                    <p class = "text-justify">{{ item.description }}</p> <br/>
                    <hr/>
                    <b>{{ item.artType }}</b>
                    <p>{{ item.size }} - {{ item.medium }}</p>
                    <p>{{ item.price }} MMK</p>
                    <button class="bg-pink-200 text-pink-950 px-2 py-2 m-1 shadow-xl sm:rounded-lg"> 
                      <!-- <Link :href="/"  >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                          <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        
                      </Link>               
                    </button>
                    <button class="bg-pink-200 text-pink-950 px-2 py-2 m-1 shadow-xl sm:rounded-lg"> 
                      <Link :href="/" :data="{ edit: true }" >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                          <path d="m2.695 14.762-1.262 3.155a.5.5 0 0 0 .65.65l3.155-1.262a4 4 0 0 0 1.343-.886L17.5 5.501a2.121 2.121 0 0 0-3-3L3.58 13.419a4 4 0 0 0-.885 1.343Z" />
                        </svg>
                      </Link>               
                    </button>
                    <button class="bg-pink-200 text-pink-950 px-2 py-2 m-1 shadow-xl sm:rounded-lg"> 
                      <Link :href="/" >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                          <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 0 0 6 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 1 0 .23 1.482l.149-.022.841 10.518A2.75 2.75 0 0 0 7.596 19h4.807a2.75 2.75 0 0 0 2.742-2.53l.841-10.52.149.023a.75.75 0 0 0 .23-1.482A41.03 41.03 0 0 0 14 4.193V3.75A2.75 2.75 0 0 0 11.25 1h-2.5ZM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4ZM8.58 7.72a.75.75 0 0 0-1.5.06l.3 7.5a.75.75 0 1 0 1.5-.06l-.3-7.5Zm4.34.06a.75.75 0 1 0-1.5-.06l-.3 7.5a.75.75 0 1 0 1.5.06l.3-7.5Z" clip-rule="evenodd" />
                        </svg>
                      </Link> -->
                    </button>
                  </div>
                  

                </div>
              </div>

            </div>
          </div>
        
    </AppLayout>
</template>


