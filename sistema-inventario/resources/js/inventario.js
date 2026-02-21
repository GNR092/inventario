/* Halo mouse */
const halo=document.getElementById("mouseHalo");
window.addEventListener("mousemove",e=>{
    halo.style.left=e.clientX+"px";
    halo.style.top=e.clientY+"px";
});

/* ===============================
   🌌 PARTÍCULAS TELARAÑA REAL
=================================*/

const canvas = document.getElementById('particlesCanvas');
const ctx = canvas.getContext('2d');

let w = canvas.width = window.innerWidth;
let h = canvas.height = window.innerHeight;

window.addEventListener('resize', ()=>{
    w = canvas.width = window.innerWidth;
    h = canvas.height = window.innerHeight;
});

const mouse = { x:w/2, y:h/2 };

window.addEventListener('mousemove', e=>{
    mouse.x = e.clientX;
    mouse.y = e.clientY;
});

const particles = [];
const count = 130;

function rand(min,max){
    return Math.random()*(max-min)+min;
}

for(let i=0;i<count;i++){
    particles.push({
        x:rand(0,w),
        y:rand(0,h),
        vx:rand(-0.4,0.4),
        vy:rand(-0.4,0.4),
        r:rand(1,2)
    });
}

function animate(){
    ctx.clearRect(0,0,w,h);

    particles.forEach(p=>{
        p.x += p.vx;
        p.y += p.vy;

        if(p.x<0||p.x>w) p.vx*=-1;
        if(p.y<0||p.y>h) p.vy*=-1;

        const dx = p.x - mouse.x;
        const dy = p.y - mouse.y;
        const dist = Math.sqrt(dx*dx + dy*dy);
        const influence = 120;

        if(dist < influence){
            const force = (1 - dist/influence) * 0.06;
            p.vx += (dx/dist) * force;
            p.vy += (dy/dist) * force;
        }
    });

    for(let i=0;i<particles.length;i++){
        for(let j=i+1;j<particles.length;j++){

            const dx = particles[i].x - particles[j].x;
            const dy = particles[i].y - particles[j].y;
            const dist = Math.sqrt(dx*dx + dy*dy);
            const maxDist = 120;

            if(dist < maxDist){
                const alpha = (1 - dist/maxDist) * 0.25;
                ctx.strokeStyle = `rgba(212,175,55,${alpha})`;
                ctx.lineWidth = 1;
                ctx.beginPath();
                ctx.moveTo(particles[i].x, particles[i].y);
                ctx.lineTo(particles[j].x, particles[j].y);
                ctx.stroke();
            }
        }
    }

    particles.forEach(p=>{
        ctx.beginPath();
        ctx.arc(p.x,p.y,p.r,0,Math.PI*2);
        ctx.fillStyle="rgba(212,175,55,0.6)";
        ctx.fill();
    });

    requestAnimationFrame(animate);
}

animate();

/* ========================= */
/* SCROLL HORIZONTAL DRAG */
/* ========================= */

const slider = document.getElementById('tableScroll');

let isDown = false;
let startX;
let scrollLeft;

slider.addEventListener('mousedown', (e) => {
    isDown = true;
    slider.classList.add('active');
    startX = e.pageX - slider.offsetLeft;
    scrollLeft = slider.scrollLeft;
});

slider.addEventListener('mouseleave', () => {
    isDown = false;
    slider.classList.remove('active');
});

slider.addEventListener('mouseup', () => {
    isDown = false;
    slider.classList.remove('active');
});

slider.addEventListener('mousemove', (e) => {
    if (!isDown) return;
    e.preventDefault();
    const x = e.pageX - slider.offsetLeft;
    const walk = (x - startX) * 1.5; // velocidad del drag
    slider.scrollLeft = scrollLeft - walk;
});

/* ========================= */
/* CR3AT3 */
/* ========================= */
document.getElementById('factura').addEventListener('change', function() {
    const fileName = this.files[0]?.name || 'Ningún archivo seleccionado';
    document.getElementById('file-name').textContent = fileName;
});

