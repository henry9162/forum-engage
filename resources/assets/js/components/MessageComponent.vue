<template>
    <div class="bg-grey-light py-8 w-full flex justify-center items-center shadow-sm chat-box">
      <div class="bg-white rounded w-full shadow hover:shadow-md duration-4 chat-box">
        <div class="flex flex-row justify-between uppercase bg-grey-lighter font-bold text-blue-dark border-b p-4">
            <div class="font-extrabold text-black shadow-sm" :class="{'pt-3':session.block}">
                {{friend.name}} <span class="text-red-300" v-if="session.block">(Blocked)</span>
                <div class="mt-2 lowercase" style="color: green" v-if="isTyping">is typing . . .</div>
            </div>

            <!-- Options -->
            <div class="cursor-pointer flex cursor-pointer text-grey-dark hover:text-blue duration-4">
                  <a href="#" @click="active = !active"><i class="fas fa-ellipsis-v pr-10 focus:outline-0" aria-hidden="true"></i></a>

                  <div class="relative cursor-pointer z-10">
                      <div v-show="active" class="bg-blue-light p-4 text-white shadow absolute mt-6 pin-r pin-t w-48"
                            style="top: 9px; right: 4px;"
                      >
                          <ul class="list-reset">
                              <li class="text-sm pb-3">
                                  <a class="text-white text-xs hover:underline" href="#" v-if="session.block && can" @click.prevent="unblock">UnBlock</a>
                                  <a class="text-white text-xs hover:underline" href="#" @click.prevent="block" v-if="!session.block">Block</a>
                              </li>
    
                              <li class="text-sm">
                                  <a class="text-white text-xs hover:underline" href="#" @click.prevent="clear"> Clear Chat</a>
                              </li>
                          </ul>
                      </div>

                      <!-- Close Button -->
                      <a href="" @click.prevent="close">
                          <i class="fa fa-times float-right" aria-hidden="true"></i>
                      </a>
                      <!-- Close Button Ends -->
                  </div>
            </div>
            <!-- Options Ends -->

        </div>

        <div class="p-6 text-grey-darker text-justify chat-body" v-chat-scroll>
            <div class="flex mb-2"  :class="{'justify-end':chat.type == 0}" v-for="chat in chats" :key="chat.id">
              <div class="py-2 px-3 rounded" :class="{'own-message':chat.type == 0, 'recieved-message':chat.type == 1}">
                <div class="flex">
                    <div>
                      <p class="text-sm text-black mt-1" :class="{'text-right':chat.type == 0,'text-green-300':chat.read_at != null}">
                        {{chat.message}}
                      </p>

                      <p class="text-right text-xs text-grey-dark mt-1">
                          {{chat.send_at}}
                      </p>
                    </div>

                    <div class="ml-2 mt-2" v-if="chat.read_at">
                      <i class="fas fa-check-double text-green-dark"></i>
                    </div>
                    <div class="ml-2 mt-2" v-else>
                      <i class="fas fa-check text-green-dark"></i>
                    </div>
                </div>
              </div>
 
            </div>
        </div>

        <div class="p-2 text-grey-darker bg-grey-lighter border-t relative">
          <div class="flex">
            <button style="outline: none" class="ml-2 px-2 mr-4 shadow border-2 bg-blue-dark rounded text-2xl" @click.prevent="toogleEmojiDialog">😃</button>
            <form class="flex-grow" @submit.prevent="send">
                <input type="text" class="block h-12 w-full leading-relaxed pin-b rounded bg-white border-none pl-6 pr-10 text-black" placeholder="Write your message here"
                :disabled="session.block" v-model="message">
            </form>
          </div>

          <div class="absolute" style="top: -425px; left: 0px" :hidden="emojiDialogHidden">
            <VEmojiPicker :pack="emojisNative" labelSearch="Search" @select="onSelectEmoji"/>
          </div>
        </div>

      </div>
    </div>
</template>

<script>
import VEmojiPicker from "v-emoji-picker";
import packEmoji from "v-emoji-picker/data/emojis.js";

export default {
  props: ["friend"],

  data() {
    return {
      active: false,
      chats: [],
      message: null,
      isTyping: false,
      emojiDialogHidden: true
    };
  },

  computed: {
    session() {
      return this.friend.session;
    },

    can() {
      return this.session.blocked_by == auth.id;
    },

    emojisNative() {
      return packEmoji;
    }
  },
  watch: {
    message(value) {
      if (value) {
        Echo.private(`Chat.${this.friend.session.id}`).whisper("typing", {
          typing: true
        });
      }
    }
  },
  methods: {
    toogleEmojiDialog() {
      this.emojiDialogHidden = !this.emojiDialogHidden;
    },

    onSelectEmoji(dataEmoji) {
      this.message ? this.message += dataEmoji.emoji : this.message = dataEmoji.emoji ;
      //this.toogleEmojiDialog();
    },

    send() {
      if (this.message) {
        this.pushToChats(this.message);
        axios
          .post(`/send/${this.friend.session.id}`, {
            content: this.message,
            to_user: this.friend.id
          })
          .then(res => (this.chats[this.chats.length - 1].id = res.data));
        this.message = null;
      }
    },

    pushToChats(message) {
      this.chats.push({
        message: message,
        type: 0,
        read_at: null,
        sent_at: "Just Now"
      });
    },

    close() {
      this.$emit("close");
    },

    clear() {
      axios
        .post(`/session/${this.friend.session.id}/clear`)
        .then(res => (this.chats = []));
    },

    block() {
      this.session.block = true;
      axios
        .post(`/session/${this.friend.session.id}/block`)
        .then(res => (this.session.blocked_by = auth.id));
    },

    unblock() {
      this.session.block = false;
      axios
        .post(`/session/${this.friend.session.id}/unblock`)
        .then(res => (this.session.blocked_by = null));
    },

    getAllMessages() {
      axios
        .post(`/session/${this.friend.session.id}/chats`)
        .then(res => (this.chats = res.data.data));
    },

    read() {
      axios.post(`/session/${this.friend.session.id}/read`);
    }
  },
  
  created() {
    this.read();

    this.getAllMessages();

    let _this = this;

    Echo.private(`Chat.${this.friend.session.id}`).listen(
      "PrivateChatEvent",
      e => {
        this.friend.session.open ? this.read() : "";
        this.chats.push({ message: e.content, type: 1, sent_at: "Just Now" });
      }
    );

    Echo.private(`Chat.${this.friend.session.id}`).listen("MsgReadEvent", e =>
      this.chats.forEach(
        chat => (chat.id == e.chat.id ? (chat.read_at = e.chat.read_at) : "")
      )
    );

    Echo.private(`Chat.${this.friend.session.id}`).listen(
      "BlockEvent",
      e => (this.session.block = e.blocked)
    );

    Echo.private(`Chat.${this.friend.session.id}`).listenForWhisper("typing", e => {

        this.isTyping = true;

        setTimeout(() => {
          _this.isTyping = false;
        }, 7000);

      }
    );
  }
};
</script>

<style>
.chat-box {
  height: 500px;
}
.chat-body {
  overflow-y: scroll;
  height: 385px;
  /* background-color: #DAD3CC; */
  background-image: url('https://i.pinimg.com/736x/a7/84/cd/a784cde8e70c9922c012886faa8c127b.jpg');
}
.duration-4 {
  transition-duration: 0.4s;
}
.sendBgColor {
  background-color: #F2F2F2
} 
.receiveBgColor {
  background-color: #E2F7CB
}
.own-message {
  background-color: #F2F2F2;
} 
.recieved-message {
  background-color: #E2F7CB;
}
</style>
