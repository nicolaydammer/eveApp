import{j as e}from"./app-9sF6Of1R.js";import{r as c,f as C}from"./vendor-D-91PCVR.js";import{T as w,I as M}from"./ThemeToggle-Cq5Qoc6g.js";import{A as I}from"./AppLayout-Bj2MQfCZ.js";import{a as N}from"./axios-A3imqcJN.js";import{a as R,C as D}from"./chevrons-right-hLe39MV5.js";import{c as k}from"./chevron-right-Bi93QP8G.js";/**
 * @license lucide-react v1.16.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const L=[["path",{d:"M5 12h14",key:"1ays0h"}],["path",{d:"M12 5v14",key:"s699le"}]],E=k("plus",L);/**
 * @license lucide-react v1.16.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const A=[["path",{d:"M10 11v6",key:"nco0om"}],["path",{d:"M14 11v6",key:"outv1u"}],["path",{d:"M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6",key:"miytrc"}],["path",{d:"M3 6h18",key:"d0wm0j"}],["path",{d:"M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2",key:"e791ji"}]],T=k("trash-2",A);async function _(o=""){return(await N.get("/eve/regions",{params:{search:o}})).data}async function $(){return(await N.get("/admin/market_regions")).data.configuration??[]}async function K(o){await N.post("/admin/market_regions",{configuration:o})}async function P(){return(await N.get("/admin/structure_markets")).data.configuration??[]}async function V(o){await N.post("/admin/structure_markets",{configuration:o})}function W(){return e.jsx(I,{children:e.jsxs("div",{className:"p-6 space-y-6",children:[e.jsxs("div",{className:"flex items-center justify-between",children:[e.jsx("h1",{className:"text-2xl font-bold",children:"Market Settings"}),e.jsx(w,{})]}),e.jsxs("div",{className:"grid grid-cols-1 xl:grid-cols-2 gap-6",children:[e.jsx(F,{}),e.jsx(H,{})]})]})})}function F(){const[o,l]=c.useState([]),[d,x]=c.useState([]),[s,g]=c.useState([]),[u,j]=c.useState([]),[m,r]=c.useState([]),[y,f]=c.useState(!0),[h,p]=c.useState("");c.useEffect(()=>{Promise.all([_(),$()]).then(([i,v])=>{l(i),x(i),g(v)}).finally(()=>f(!1))},[]),c.useEffect(()=>{const i=setTimeout(async()=>{try{const v=await _(h);x(v)}catch(v){console.error("Failed to fetch regions",v)}},250);return()=>clearTimeout(i)},[h]);const b=c.useMemo(()=>d.filter(i=>!s.includes(i._key)),[d,s]),t=c.useMemo(()=>o.filter(i=>s.includes(i._key)),[o,s]),n=i=>{g(i),K(i)},a=()=>{if(u.length===0)return;const i=[...new Set([...s,...u])];n(i),j([]),p("")},S=()=>{if(m.length===0)return;const i=s.filter(v=>!m.includes(v));n(i),r([])};return y?e.jsx("div",{className:"border border-zinc-800 rounded-lg p-5",children:"Loading regions..."}):e.jsxs("div",{className:"border border-zinc-800 rounded-lg",children:[e.jsxs("div",{className:"p-4 border-b border-zinc-800",children:[e.jsx("h2",{className:"font-semibold",children:"Region Market Sync"}),e.jsx("p",{className:"text-sm text-zinc-400 mt-1",children:"Select which regions should have their market orders synchronized."})]}),e.jsxs("div",{className:"p-4 grid grid-cols-[1fr_auto_1fr] gap-4 items-center",children:[e.jsx(z,{title:"Available",regions:b,selected:u,setSelected:j,regionSearch:h,setRegionSearch:p}),e.jsxs("div",{className:"flex flex-col gap-3",children:[e.jsx("button",{type:"button",onClick:a,disabled:u.length===0,className:`
                            p-2 rounded border border-zinc-700
                            hover:bg-zinc-800
                            disabled:opacity-30
                            disabled:cursor-not-allowed
                        `,children:e.jsx(R,{size:20})}),e.jsx("button",{type:"button",onClick:S,disabled:m.length===0,className:`
                            p-2 rounded border border-zinc-700
                            hover:bg-zinc-800
                            disabled:opacity-30
                            disabled:cursor-not-allowed
                        `,children:e.jsx(D,{size:20})})]}),e.jsx(z,{title:"Synchronized",regions:t,selected:m,setSelected:r})]})]})}function z({title:o,regions:l,selected:d,setSelected:x,regionSearch:s,setRegionSearch:g}){const[u,j]=c.useState(null),m=(r,y,f)=>{if(f&&u!==null){const h=l.findIndex(b=>b._key===u),p=l.findIndex(b=>b._key===r);if(h!==-1&&p!==-1){const b=Math.min(h,p),t=Math.max(h,p),n=l.slice(b,t+1).map(a=>a._key);x([...new Set([...d,...n])]);return}}if(j(r),!y){x([r]);return}if(d.includes(r)){x(d.filter(h=>h!==r));return}x([...d,r])};return e.jsxs("div",{children:[e.jsx("h3",{className:"text-sm font-medium mb-2",children:o}),s!==void 0&&g&&e.jsx(M,{type:"text",placeholder:"Search regions...",value:s,onChange:r=>g(r.target.value)}),e.jsxs("div",{className:"h-80 overflow-y-auto border border-zinc-800 rounded bg-zinc-950 p-1",children:[l.map(r=>{const y=d.includes(r._key);return e.jsxs("button",{type:"button",onClick:f=>m(r._key,f.ctrlKey||f.metaKey,f.shiftKey),className:`
                                w-full text-left px-3 py-2 rounded text-sm
                                ${y?"bg-zinc-700":"hover:bg-zinc-900"}
                            `,children:[e.jsx("div",{children:r.region}),e.jsx("div",{className:"text-xs text-zinc-500",children:r._key})]},r._key)}),l.length===0&&e.jsx("div",{className:"h-full flex items-center justify-center text-sm text-zinc-500",children:"Empty"})]})]})}function H(){const{auth:o}=C().props,l=o.user.characters,[d,x]=c.useState([]),[s,g]=c.useState(null),[u,j]=c.useState(""),[m,r]=c.useState(""),[y,f]=c.useState(!0);c.useEffect(()=>{P().then(t=>{x(t.map(n=>{const a=l.find(S=>S.CharacterID===n.char);return{structure_id:n.structure,character_id:n.char,character_name:(a==null?void 0:a.CharacterName)??"Unknown character"}}))}).finally(()=>f(!1))},[l]);const h=t=>{x(t);const n=t.map(a=>({structure:a.structure_id,char:a.character_id}));V(n)},p=()=>{if(!u||!m)return;const t=l.find(a=>a.CharacterID===Number(m));if(!t)return;const n={structure_id:Number(u),character_id:t.CharacterID,character_name:t.CharacterName};d.some(a=>a.structure_id===n.structure_id&&a.character_id===n.character_id)||(h([...d,n]),j(""),r(""))},b=()=>{if(!s)return;const t=d.filter(n=>!(n.structure_id===s.structure_id&&n.character_id===s.character_id));h(t),g(null)};return y?e.jsx("div",{className:"border border-zinc-800 rounded-lg p-5",children:"Loading structures..."}):e.jsxs("div",{className:"border border-zinc-800 rounded-lg",children:[e.jsxs("div",{className:"p-4 border-b border-zinc-800",children:[e.jsx("h2",{className:"font-semibold",children:"Structure Market Sync"}),e.jsx("p",{className:"text-sm text-zinc-400 mt-1",children:"Configure structures and which of your characters should synchronize them."})]}),e.jsxs("div",{className:"p-4 space-y-5",children:[e.jsxs("div",{children:[e.jsx("h3",{className:"text-sm font-medium mb-2",children:"Configured Structures"}),e.jsxs("div",{className:"border border-zinc-800 rounded overflow-hidden",children:[e.jsxs("div",{className:"grid grid-cols-2 px-3 py-2 bg-zinc-900 text-sm font-semibold",children:[e.jsx("div",{children:"Structure ID"}),e.jsx("div",{children:"Character"})]}),e.jsxs("div",{className:"h-56 overflow-y-auto bg-zinc-950",children:[d.map(t=>{const n=(s==null?void 0:s.structure_id)===t.structure_id&&(s==null?void 0:s.character_id)===t.character_id;return e.jsxs("button",{type:"button",onClick:()=>g(n?null:t),className:`
                                            grid grid-cols-2
                                            w-full text-left
                                            px-3 py-2 text-sm
                                            border-t border-zinc-900
                                            ${n?"bg-zinc-700":"hover:bg-zinc-900"}
                                        `,children:[e.jsx("div",{children:t.structure_id}),e.jsxs("div",{children:[t.character_name,e.jsxs("span",{className:"text-zinc-500 ml-2",children:["(",t.character_id,")"]})]})]},`${t.structure_id}-${t.character_id}`)}),d.length===0&&e.jsx("div",{className:"h-full flex items-center justify-center text-sm text-zinc-500",children:"No structures configured"})]})]}),e.jsx("div",{className:"flex justify-end mt-3",children:e.jsxs("button",{type:"button",onClick:b,disabled:!s,className:`
                                flex items-center gap-2
                                px-3 py-2 rounded
                                border border-zinc-700
                                text-sm text-red-400
                                hover:bg-red-900/20
                                disabled:opacity-30
                                disabled:cursor-not-allowed
                            `,children:[e.jsx(T,{size:16}),"Remove"]})})]}),e.jsxs("div",{className:"border-t border-zinc-800 pt-4",children:[e.jsx("h3",{className:"text-sm font-medium mb-3",children:"Add Structure"}),e.jsxs("div",{className:"space-y-3",children:[e.jsxs("div",{children:[e.jsx("label",{className:"block text-sm mb-1",children:"Structure ID"}),e.jsx("input",{type:"text",inputMode:"numeric",value:u,onChange:t=>j(t.target.value.replace(/\D/g,"")),placeholder:"Structure ID",className:`
                                    w-full px-3 py-2 rounded
                                    border border-zinc-700
                                    bg-zinc-950
                                    text-sm
                                    outline-none
                                    focus:border-zinc-500
                                `})]}),e.jsxs("div",{children:[e.jsx("label",{className:"block text-sm mb-1",children:"Character"}),e.jsxs("select",{value:m,onChange:t=>r(t.target.value),className:`
                                    w-full px-3 py-2 rounded
                                    border border-zinc-700
                                    bg-zinc-950
                                    text-sm
                                    outline-none
                                    focus:border-zinc-500
                                `,children:[e.jsx("option",{value:"",children:"Select character"}),l.map(t=>e.jsxs("option",{value:t.CharacterID,children:[t.CharacterName," (",t.CharacterID,")"]},t.CharacterID))]})]}),e.jsx("div",{className:"flex justify-end",children:e.jsxs("button",{type:"button",onClick:p,disabled:!u||!m,className:`
                                    flex items-center gap-2
                                    px-3 py-2 rounded
                                    border border-zinc-700
                                    text-sm
                                    hover:bg-zinc-800
                                    disabled:opacity-30
                                    disabled:cursor-not-allowed
                                `,children:[e.jsx(E,{size:16}),"Add"]})})]})]})]})]})}export{W as default};
