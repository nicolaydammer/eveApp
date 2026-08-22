import{j as e}from"./app-DQOMDmna.js";import{f as N,r as c,L as v}from"./vendor-D-91PCVR.js";import{a as g}from"./axios-A3imqcJN.js";import{A as y}from"./AppLayout-B69dHAsO.js";import{T as z}from"./ThemeToggle-BlPN1t03.js";import"./chevron-right-Bi93QP8G.js";async function w(s="",n=1){return(await g.get("/admin/users/list",{params:{search:s||void 0,page:n}})).data}async function _(s,n){await g.patch(`/admin/users/${s}/admin`,{is_admin:n})}function E(){const{auth:s}=N().props,[n,r]=c.useState(null),[i,m]=c.useState(()=>new URLSearchParams(window.location.search).get("search")??""),[o,x]=c.useState(!0),[h,l]=c.useState(null),[p,u]=c.useState(new Set);c.useEffect(()=>{const a=setTimeout(()=>{b(1)},250);return()=>clearTimeout(a)},[i]);const b=async a=>{x(!0);try{const d=await w(i,a);r(d),i.trim()?u(new Set(d.data.map(t=>t.id))):u(new Set)}finally{x(!1)}},f=a=>{u(d=>{const t=new Set(d);return t.has(a)?t.delete(a):t.add(a),t})},j=async a=>{l(a.id);try{await _(a.id,!a.is_admin),r(d=>d&&{...d,data:d.data.map(t=>t.id===a.id?{...t,is_admin:!t.is_admin}:t)})}finally{l(null)}};return e.jsx(y,{children:e.jsxs("div",{className:"p-6 space-y-6",children:[e.jsxs("div",{className:"flex items-center justify-between",children:[e.jsx("h1",{className:"text-2xl font-bold",children:"User Administration"}),e.jsx(z,{})]}),e.jsxs("div",{className:"border border-zinc-800 rounded-lg",children:[e.jsxs("div",{className:"p-4 border-b border-zinc-800 space-y-3",children:[e.jsxs("div",{children:[e.jsx("h2",{className:"font-semibold",children:"Users"}),e.jsx("p",{className:"text-sm text-zinc-400 mt-1",children:"Manage administrator roles and characters."})]}),e.jsx("input",{type:"text",value:i,onChange:a=>{m(a.target.value)},placeholder:"Search by character...",className:`
                                w-full
                                px-3 py-2
                                rounded
                                bg-zinc-950
                                border border-zinc-800
                                text-sm
                                placeholder:text-zinc-500
                                focus:outline-none
                                focus:border-zinc-600
                            `})]}),e.jsx("div",{children:o?e.jsx("div",{className:"p-5 text-sm text-zinc-400",children:"Loading users..."}):(n==null?void 0:n.data.length)===0?e.jsx("div",{className:"p-5 text-sm text-zinc-400",children:"No users found."}):n==null?void 0:n.data.map(a=>e.jsx(U,{user:a,isCurrentUser:a.id===s.user.id,expanded:p.has(a.id),search:i,updating:h===a.id,onToggleExpanded:f,onToggleAdmin:j},a.id))}),n&&n.last_page>1&&e.jsxs("div",{className:"flex items-center justify-between p-4 border-t border-zinc-800",children:[e.jsxs("span",{className:"text-sm text-zinc-400",children:["Page ",n.current_page," of"," ",n.last_page]}),e.jsxs("div",{className:"flex gap-2",children:[e.jsx("button",{type:"button",disabled:o||n.current_page===1,onClick:()=>b(n.current_page-1),className:`
                                        px-3 py-2
                                        rounded
                                        border border-zinc-700
                                        hover:bg-zinc-900
                                        disabled:opacity-40
                                        disabled:cursor-not-allowed
                                    `,children:"Previous"}),e.jsx("button",{type:"button",disabled:o||n.current_page===n.last_page,onClick:()=>b(n.current_page+1),className:`
                                        px-3 py-2
                                        rounded
                                        border border-zinc-700
                                        hover:bg-zinc-900
                                        disabled:opacity-40
                                        disabled:cursor-not-allowed
                                    `,children:"Next"})]})]})]})]})})}function U({user:s,isCurrentUser:n,expanded:r,search:i,updating:m,onToggleExpanded:o,onToggleAdmin:x}){var l;const h=n&&s.is_admin;return e.jsxs("div",{className:"border-b border-zinc-900 last:border-b-0",children:[e.jsxs("div",{className:"grid grid-cols-[1fr_auto] items-center px-4 py-3",children:[e.jsxs("button",{type:"button",onClick:()=>o(s.id),className:`
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
                        `,children:"▶"}),e.jsxs("div",{className:"min-w-0",children:[e.jsx("div",{className:"font-medium",children:((l=s.main_character)==null?void 0:l.CharacterName)??"No main character"}),e.jsxs("div",{className:"text-xs text-zinc-500",children:["User #",s.id,n&&" · You"," · ",s.characters.length," ",s.characters.length===1?"character":"characters"]})]})]}),e.jsx("button",{type:"button",disabled:m||h,onClick:()=>x(s),className:`
                        px-3 py-2
                        rounded
                        border border-zinc-700
                        hover:bg-zinc-900
                        disabled:opacity-40
                        disabled:cursor-not-allowed
                    `,children:m?"Updating...":s.is_admin?"Remove Admin":"Make Admin"})]}),r&&e.jsx("div",{className:"px-10 pb-3 space-y-1",children:s.characters.map(p=>e.jsx(C,{character:p,search:i},p.CharacterID))})]})}function C({character:s,search:n}){const r=n.trim().toLowerCase(),i=r.length>0&&s.CharacterName.toLowerCase().includes(r);return e.jsx("div",{className:`
            text-sm
            px-3 py-1.5
            rounded
            ${i?"text-zinc-100 bg-zinc-800":"text-zinc-400"}
        `,children:e.jsx(v,{href:`/admin/characters?search=${encodeURIComponent(s.CharacterName)}`,className:"hover:text-zinc-100 hover:underline",children:s.CharacterName})})}export{E as default};
