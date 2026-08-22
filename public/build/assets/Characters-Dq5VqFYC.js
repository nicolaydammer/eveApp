import{j as e}from"./app-DQOMDmna.js";import{r as d,L as y}from"./vendor-D-91PCVR.js";import{a as N}from"./axios-A3imqcJN.js";import{A as C}from"./AppLayout-B69dHAsO.js";import{T as z}from"./ThemeToggle-BlPN1t03.js";import"./chevron-right-Bi93QP8G.js";async function w(s="",r=1){return(await N.get("/admin/characters/list",{params:{search:s||void 0,page:r}})).data}async function T(s){var r;try{const t=await N.get(`/admin/characters/${s}/test-token`);return"success"}catch(t){return((r=t.response)==null?void 0:r.status)===429?"rate_limited":"failed"}}function M(){const[s,r]=d.useState(null),[t,x]=d.useState(()=>new URLSearchParams(window.location.search).get("search")??""),[a,u]=d.useState(!0),[b,o]=d.useState(new Set),[g,m]=d.useState(null),[p,h]=d.useState(new Map);d.useEffect(()=>{const n=setTimeout(()=>{c(1)},250);return()=>clearTimeout(n)},[t]);const c=async n=>{u(!0);try{const l=await w(t,n);r(l),t.trim()?o(new Set(l.data.map(i=>i.CharacterID))):o(new Set)}finally{u(!1)}},f=n=>{o(l=>{const i=new Set(l);return i.has(n)?i.delete(n):i.add(n),i})},v=async n=>{m(n);try{const l=await T(n);h(i=>{const j=new Map(i);return j.set(n,l),j})}finally{m(null)}};return e.jsx(C,{children:e.jsxs("div",{className:"p-6 space-y-6",children:[e.jsxs("div",{className:"flex items-center justify-between",children:[e.jsx("h1",{className:"text-2xl font-bold",children:"Character Administration"}),e.jsx(z,{})]}),e.jsxs("div",{className:"border border-zinc-800 rounded-lg",children:[e.jsxs("div",{className:"p-4 border-b border-zinc-800 space-y-3",children:[e.jsxs("div",{children:[e.jsx("h2",{className:"font-semibold",children:"Characters"}),e.jsx("p",{className:"text-sm text-zinc-400 mt-1",children:"Manage characters, ownership and ESI authentication."})]}),e.jsx("input",{type:"text",value:t,onChange:n=>{x(n.target.value)},placeholder:"Search by character...",className:`
                                w-full
                                px-3 py-2
                                rounded
                                bg-zinc-950
                                border border-zinc-800
                                text-sm
                                placeholder:text-zinc-500
                                focus:outline-none
                                focus:border-zinc-600
                            `})]}),e.jsx("div",{children:a?e.jsx("div",{className:"p-5 text-sm text-zinc-400",children:"Loading characters..."}):(s==null?void 0:s.data.length)===0?e.jsx("div",{className:"p-5 text-sm text-zinc-400",children:"No characters found."}):s==null?void 0:s.data.map(n=>e.jsx(S,{character:n,expanded:b.has(n.CharacterID),search:t,testing:g===n.CharacterID,tokenResult:p.get(n.CharacterID),onToggleExpanded:f,onTestToken:v},n.CharacterID))}),s&&s.last_page>1&&e.jsxs("div",{className:"flex items-center justify-between p-4 border-t border-zinc-800",children:[e.jsxs("span",{className:"text-sm text-zinc-400",children:["Page ",s.current_page," of"," ",s.last_page]}),e.jsxs("div",{className:"flex gap-2",children:[e.jsx("button",{type:"button",disabled:a||s.current_page===1,onClick:()=>c(s.current_page-1),className:`
                                        px-3 py-2
                                        rounded
                                        border border-zinc-700
                                        hover:bg-zinc-900
                                        disabled:opacity-40
                                        disabled:cursor-not-allowed
                                    `,children:"Previous"}),e.jsx("button",{type:"button",disabled:a||s.current_page===s.last_page,onClick:()=>c(s.current_page+1),className:`
                                        px-3 py-2
                                        rounded
                                        border border-zinc-700
                                        hover:bg-zinc-900
                                        disabled:opacity-40
                                        disabled:cursor-not-allowed
                                    `,children:"Next"})]})]})]})]})})}function S({character:s,expanded:r,search:t,testing:x,tokenResult:a,onToggleExpanded:u,onTestToken:b}){var m,p,h;const o=t.trim().toLowerCase(),g=o.length>0&&s.CharacterName.toLowerCase().includes(o);return e.jsxs("div",{className:"border-b border-zinc-900 last:border-b-0",children:[e.jsxs("div",{className:"grid grid-cols-[1fr_auto] items-center px-4 py-3",children:[e.jsxs("button",{type:"button",onClick:()=>u(s.CharacterID),className:`
                        flex
                        items-center
                        gap-3
                        min-w-0
                        text-left
                        hover:text-zinc-300
                    `,children:[e.jsx("span",{className:`
                            text-xs
                            transition-transform
                            ${r?"rotate-90":""}
                        `,children:"▶"}),e.jsxs("div",{className:"min-w-0",children:[e.jsx("div",{className:g?"font-medium text-zinc-100":"font-medium",children:s.CharacterName}),e.jsxs("div",{className:"text-xs text-zinc-500",children:["Character #",s.CharacterID," · ",s.user?`User #${s.user.id}`:"No user"," · ",(m=s.user)!=null&&m.main_character_id?"Main":"Alt"]})]})]}),e.jsx("button",{type:"button",disabled:x,onClick:()=>b(s.CharacterID),className:`
                        px-3 py-2
                        rounded
                        border border-zinc-700
                        hover:bg-zinc-900
                        disabled:opacity-40
                        disabled:cursor-not-allowed
                    `,children:x?"Testing...":a===void 0?"Test Token":a==="success"?"Token OK":a==="rate_limited"?"Rate Limited":"Token Failed"})]}),r&&e.jsxs("div",{className:"px-10 pb-4 space-y-4",children:[e.jsxs("div",{className:"space-y-1",children:[e.jsx("div",{className:"text-sm font-medium",children:"Character"}),e.jsxs("div",{className:"text-sm text-zinc-400",children:["ID: ",s.CharacterID]}),e.jsxs("div",{className:"text-sm text-zinc-400",children:["Main character:"," ",(p=s.user)!=null&&p.main_character_id?"Yes":"No"]}),s.user&&e.jsxs(y,{href:`/admin/users?search=${encodeURIComponent(s.user.main_character.CharacterName)}`,className:`
                                    inline-block
                                    text-sm
                                    text-zinc-300
                                    hover:text-zinc-100
                                    underline
                                `,children:["View User #",s.user.id]})]}),e.jsxs("div",{className:"space-y-2",children:[e.jsx("div",{className:"text-sm font-medium",children:"ESI Scopes"}),((h=s.scopes)==null?void 0:h.length)===0||s.scopes===null?e.jsx("div",{className:"text-sm text-zinc-500",children:"No scopes available."}):e.jsx("div",{className:"space-y-1",children:e.jsx("div",{className:"grid grid-cols-4 gap-1",children:s.scopes.map(c=>e.jsx("div",{className:`
                                            text-sm
                                            text-zinc-400
                                            px-3 py-1.5
                                            rounded
                                            bg-zinc-900
                                            border border-zinc-800
                                            truncate
                                        `,title:c,children:c},c))})})]}),a!==void 0&&e.jsxs("div",{className:"text-sm",children:["Token status:"," ",e.jsx("span",{className:a?"text-green-500":"text-red-500",children:a?"Working":"Invalid / Failed"})]})]})]})}export{M as default};
