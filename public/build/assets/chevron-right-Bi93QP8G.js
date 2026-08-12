import{r as s}from"./vendor-D-91PCVR.js";/**
 * @license lucide-react v1.16.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const d=(...e)=>e.filter((t,o,r)=>!!t&&t.trim()!==""&&r.indexOf(t)===o).join(" ").trim();/**
 * @license lucide-react v1.16.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const A=e=>e.replace(/([a-z0-9])([A-Z])/g,"$1-$2").toLowerCase();/**
 * @license lucide-react v1.16.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const b=e=>e.replace(/^([A-Z])|[\s-_]+(\w)/g,(t,o,r)=>r?r.toUpperCase():o.toLowerCase());/**
 * @license lucide-react v1.16.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const u=e=>{const t=b(e);return t.charAt(0).toUpperCase()+t.slice(1)};/**
 * @license lucide-react v1.16.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */var i={xmlns:"http://www.w3.org/2000/svg",width:24,height:24,viewBox:"0 0 24 24",fill:"none",stroke:"currentColor",strokeWidth:2,strokeLinecap:"round",strokeLinejoin:"round"};/**
 * @license lucide-react v1.16.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const L=e=>{for(const t in e)if(t.startsWith("aria-")||t==="role"||t==="title")return!0;return!1},W=s.createContext({}),y=()=>s.useContext(W),S=s.forwardRef(({color:e,size:t,strokeWidth:o,absoluteStrokeWidth:r,className:a="",children:n,iconNode:m,...l},p)=>{const{size:c=24,strokeWidth:h=2,absoluteStrokeWidth:w=!1,color:x="currentColor",className:f=""}=y()??{},k=r??w?Number(o??h)*24/Number(t??c):o??h;return s.createElement("svg",{ref:p,...i,width:t??c??i.width,height:t??c??i.height,stroke:e??x,strokeWidth:k,className:d("lucide",f,a),...!n&&!L(l)&&{"aria-hidden":"true"},...l},[...m.map(([g,v])=>s.createElement(g,v)),...Array.isArray(n)?n:[n]])});/**
 * @license lucide-react v1.16.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const C=(e,t)=>{const o=s.forwardRef(({className:r,...a},n)=>s.createElement(S,{ref:n,iconNode:t,className:d(`lucide-${A(u(e))}`,`lucide-${e}`,r),...a}));return o.displayName=u(e),o};/**
 * @license lucide-react v1.16.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const _=[["path",{d:"m6 9 6 6 6-6",key:"qrunsl"}]],N=C("chevron-down",_);/**
 * @license lucide-react v1.16.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const $=[["path",{d:"m9 18 6-6-6-6",key:"mthhwq"}]],R=C("chevron-right",$);export{N as C,R as a,C as c};
