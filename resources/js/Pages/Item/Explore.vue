<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import SectionBorder from '@/Components/SectionBorder.vue';
    import Welcome from '@/Components/Welcome.vue';
    import { Link, Head } from '@inertiajs/vue3';
    import { defineProps , watch } from 'vue';
    import { reactive, ref, onMounted, watchEffect} from 'vue';
    import JetLabel from "@/Components/InputLabel.vue";

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
    
    var url = '/items/explore/filter?artType=' + encodeURIComponent(par1.options[index].text);
    window.location.href = url;
 
   
  }

</script>

<!-- resources/js/Pages/Items/Index.vue -->
<template #header>
    <AppLayout title="Item" :class="{ 'dark-mode-class': darkMode }">
        <template #header >
          <div class="grid grid-cols-4 items-center">
            <h2 class="font-semibold custom-text-xl  leading-tight">
                <Link :href="route('items.index')" class = "text-pink-900"  :class="{ 'dark:text-gray-800': darkMode }">Items</Link>
                <span class="text-gray-600"> /  Explore </span>
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
                    <p> Owned By  <b> {{ item.user.name  }}  </b></p>
                    <button class="bg-pink-200 text-pink-950 px-2 py-2 m-1 shadow-xl sm:rounded-lg"> 
                      <Link :href="route('items.view',  { id: item.id })"  >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                          <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        
                      </Link>               
                    </button>
                    
                  </div>
                  

                </div>
              </div>

            </div>
          </div>
        
    </AppLayout>
</template>


