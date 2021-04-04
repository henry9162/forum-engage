<template>
    <div>
        <div class="container mx-auto p-3 md:flex md:p-0">
          <div :class="notOpened.length < friends.length ? 'hidden md:block' : 'block'" class="w-full md:w-1/4 md:mx-3 bg-white shadow rounded-lg">
              <div class="bg-grey-lighter p-4">
                <form @submit.prevent="searchFriends()" autocomplete="on">
                    <input type="text" v-model="searchQuery" class="w-full px-4 py-3 text-xs" placeholder="Search or start new chat"/>
                </form>
              </div>
              <!-- Users -->
              <div class="bg-grey-lighter">
                  <div class="bg-white px-4 flex items-center hover:bg-grey-lighter cursor-pointer" @click.prevent="openChat(friend)" :key=friend.id v-for="friend in friends">
                      <div class="px-3 py-2">
                        <img :src="friend.avatar_path" width="36" height="36" class="bg-blue-darker rounded-full p-1">
                      </div>
                      <div class="flex-1">
                          <div class="flex items-bottom justify-between">
                              <p class="text-grey-darkest flex justify-between">
                                  <a href="" class="flex mr-2 font-bold text-black items-center text-xs text-grey-darkest hover:text-blue hover:font-black">
                                    {{friend.name}}  
                                    <i class="fa fa-circle ml-2" style="color:green" v-if="friend.online" aria-hidden="true"></i>                         
                                  </a>               
                                  <span class="bg-red text-white rounded-full py-1 px-2 text-xs" v-if="friend.session && (friend.session.unreadCount > 0)"> {{friend.session.unreadCount}}</span>
                              </p>    
                          </div>
                      </div>
                  </div>    
              </div>
          </div>

          <div :class="notOpened.length < friends.length ? 'block' : 'hidden md:block'" class="w-full md:w-3/4 md:mr-3" style="padding: 0px 0.1rem 0px">
              <div>
                  <div v-if="notOpened.length == friends.length" class="placeholder">
                    <div class="md:rounded-lg" style="background-image: url('https://i.pinimg.com/736x/a7/84/cd/a784cde8e70c9922c012886faa8c127b.jpg')">
                      <div class="text-center">
                        <img src="/images/header/header-image.png" class="">
                      </div>
                      <h4 class="text-white text-center pb-3">Kindly click on a user to start chatting</h4>
                    </div>
                  </div>

                  <span v-for="friend in friends" :key="friend.id">
                      <div v-if="friend.session">
                          <message-component v-if="friend.session.open" @close="close(friend)" :friend=friend></message-component>
                      </div> 
                  </span>
              </div>
          </div>
      </div>
    </div>
</template>

<script>
import MessageComponent from "./MessageComponent";
import ChatNotifications from "./ChatNotifications";

export default {

  components: { MessageComponent },

  data() {
    return {
      friends: [],
      searchQuery: '',
      notifications: ''
    };
  },

  watch: {
      searchQuery: {
        handler: _.debounce(function(){
          this.searchFriends();
        }, 100)
      }
  },

  computed: {
    endpoint() {
        return `/profiles/notifications/chat`;
    },
    deleteNotificationEndpoint(){
      return `/profiles/${window.auth.username}/notifications`;
    },
    notOpened() {
      return this.friends.filter(friend => { 
        if (friend.session){
          return friend.session.open == false; 
        }
      });
    }
  },

  methods: {
    searchFriends(){
      axios.post("/friends/search", { param: this.searchQuery })
            .then(res => {
                res.data == 'ok' ? console.log('Request succefully sent') : '';
            })
            .catch(error => {
              console.log(error)
            })
    },

    fetchNotifications(){
      axios.get(this.endpoint)
          .then(response => {
              this.notifications = response.data
          });
    },

    close(friend) {
      friend.session.open = false;
    },
    
    getFriends() {
      axios.post("/getFriends").then(res => {
        this.friends = res.data.data;
        this.friends.forEach(
          friend => (friend.session ? this.listenForEverySession(friend) : "")
        );
      });
    },

    openChat(friend) {

      if (friend.session) {

        this.friends.forEach((friend) => {
          friend.session ? (friend.session.open = false) : "";
        });

        friend.session.open = true;
        friend.session.unreadCount = 0;

        if (friend.session.open){
          this.notifications.forEach((notification) => {
            axios.post(`${this.deleteNotificationEndpoint}/${notification.id}`);       
          });
        }

      } else {
        this.createSession(friend);
      }
    },

    createSession(friend) {
      axios.post("/session/create", { friend_id: friend.id }).then(res => {
          alert(res);
        (friend.session = res.data.data), (friend.session.open = true);
      });
    },

    listenForEverySession(friend) {
      Echo.private(`Chat.${friend.session.id}`).listen("PrivateChatEvent",
        e => (friend.session.open ? "" : friend.session.unreadCount++)
      );
    }
  },

  created() {

    this.fetchNotifications();

    this.getFriends();

    Echo.channel("Search").listen("SearchEvent", (e) => {
          this.friends = e.searchedFriend;
          this.friends.forEach(
            friend => (friend.session ? this.listenForEverySession(friend) : "")
          );
    })

    Echo.channel("Chat").listen("SessionEvent", e => {
      let friend = this.friends.find(friend => friend.id == e.session_by);
      friend.session = e.session;
      this.listenForEverySession(friend);
    });

    Echo.join(`Chat`)
      .here(users => {
        this.friends.forEach(friend => {
          users.forEach(user => {
            if (user.id == friend.id) {
              friend.online = true;
            }
          });
        });
      })
      .joining(user => {
        this.friends.forEach(
          friend => (user.id == friend.id ? (friend.online = true) : "")
        );
      })
      .leaving(user => {
        this.friends.forEach(
          friend => (user.id == friend.id ? (friend.online = false) : "")
        );
      });
  }
};
</script>