import { createRouter, createWebHashHistory } from "vue-router";

const routes = [
    {
        name: "Login",
        path: "/login",
        component: () => import("../views/login/index.vue"),
    },
    {
        name: "Dashboard",
        path: "/",
        component: () => import("../views/layout/index.vue"),
        redirect: "/dnsMonitor",
        children: [
            {
                path: "dnsMonitor",
                name: "dnsMonitor",
                component: () => import("../views/dnsShow/dnsMonitor.vue"),
            },
            {
                path: "dnsRoot",
                name: "dnsRoot",
                component: () => import("../views/dnsShow/dnsRoot.vue"),
            },
            {
                path: "dnsAuth",
                name: "dnsAuth",
                component: () => import("../views/dnsShow/dnsAuth.vue"),
            },
            {
                path: "dnsRecurse",
                name: "dnsRecurse",
                component: () => import("../views/dnsShow/dnsRecurse.vue"),
            },
            {
                path: "dnsManagement",
                name: "dnsManagement",
                component: () => import("../views/dnsShow/dnsManagement.vue"),
            },
            {
                path: "dnsChain",
                name: "dnsChain",
                component: () => import("../views/dnsShow/dnsChain.vue"),
            },
            {
                path: "dnsWWW",
                name: "dnsWWW",
                component: () => import("../views/dnsShow/dnsWWW.vue"),
            },
            {
                path: "dnsSOA",
                name: "dnsSOA",
                component: () => import("../views/dnsShow/dnsSOA.vue"),
            },
            {
                path: "dnsDebugINFO",
                name: "dnsDebugINFO",
                component: () => import("../views/dnsShow/dnsDebugInfo.vue"),
            },
            {
                path: "dnsJSON",
                name: "dnsJSON",
                component: () => import("../views/dnsJson/dnsJSON.vue"),
            },
            {
                path: "publishConfig",
                name: "publishConfig",
                component: () =>
                    import("../views/publishConfig/publishConfig.vue"),
            },
            {
                path: "manageConfig",
                name: "manageConfig",
                component: () =>
                    import("../views/manageConfig/manageConfig.vue"),
            },
        ],
    },
];

const router = createRouter({
    history: createWebHashHistory(),
    routes,
});

export default router;
