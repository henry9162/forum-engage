<template>
  <carousel :data="adverts" indicators="hover"></carousel>
</template>

<script>
  export default {
    data() {
      return {
        adverts: [],
        data: [
            '<div class="flex items-center bg-gray-dark text-gray-100 text-2xl justify-center h-48 md:w-64 flex-shrink"><a href="#" class="h-full"><img class="w-full h-full" src="https://i.ytimg.com/vi/tDRRUS-S5Fw/hqdefault.jpg"></a></div>',
            '<div class="flex items-center bg-gray-dark text-gray-100 text-2xl justify-center h-48 md:w-64 flex-shrink"><a href="#" class="h-full"><img class="w-full h-full" src="https://investdata.com.ng/wp-content/uploads/2017/04/Zenith-Bank-ad.gif"></a></div>',
            '<div class="flex items-center bg-gray-dark text-gray-100 text-2xl justify-center h-48 md:w-64 flex-shrink"><a href="#" class="h-full"><img class="w-full h-full" src="https://theeagleonline.com.ng/wp-content/uploads/2016/09/966-Fridays.jpg"></a></div>'
        ]
      };
    },
    created() {
        // for dynamic data, fire an API call via axios to retrive avaliable adverts 
        // then append the retrived advert with its html to the data array which will be initialized
        // then perform the below instruction
        this.data.forEach(entry => {
            this.adverts.push(entry);
        })
    }
  };
</script>
