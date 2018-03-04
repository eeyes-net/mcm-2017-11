<template>
    <div>
        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <a class="nav-link" :class="{active: sidebarActive[0]}" @click="scrollToElement(0)">个人信息</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" :class="{active: sidebarActive[1]}" @click="scrollToElement(1)">我的队伍</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" :class="{active: sidebarActive[2]}" @click="scrollToElement(2)">我的招募</a>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                sidebarActive: [
                    true,
                    false,
                    false
                ],
                els: [
                    '.home-user',
                    '.home-team',
                    '.home-recruit'
                ]
            };
        },
        mounted() {
            window.addEventListener('scroll', () => {
                let scale = (document.documentElement.scrollHeight - window.innerHeight) / document.documentElement.scrollHeight;
                let scrollPos = document.documentElement.scrollTop / scale;
                // TODO
                let found = false;
                for (let i = 1; i < this.els.length; ++i) {
                    if (found) {
                        this.sidebarActive[i - 1] = false;
                    } else if (scrollPos < document.querySelector(this.els[i]).offsetTop) {
                        this.sidebarActive[i - 1] = true;
                        found = true;
                    } else {
                        this.sidebarActive[i - 1] = false;
                    }
                }
                this.sidebarActive[this.els.length - 1] = !found;
                this.$forceUpdate();
            });
        },
        methods: {
            scrollToElement(i) {
                let scale = (document.documentElement.scrollHeight - window.innerHeight) / document.documentElement.scrollHeight;
                let scrollPos = document.querySelector(this.els[i]).offsetTop * scale; // TODO
                jQuery('html, body').animate({
                    scrollTop: scrollPos + 1
                }, 500);
            }
        }
    };
</script>