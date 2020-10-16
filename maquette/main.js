const canvas = document.getElementById("mainframe");
const ctx = canvas.getContext("2d");

const paintbrushSlider = document.getElementById("paintbrushSize");
const colorPicker = document.getElementById("colorPicker");

var DRAWING = false;

var X, Y, pX, pY;

ctx.lineWidth = paintbrushSlider.value;
paintbrushSlider.onchange = () => {
    ctx.lineWidth = paintbrushSlider.value;
}

canvas.width = 500;
canvas.height = 500;

function setPosition(e) {
    pX = X;
    pY = Y;
    X = e.clientX - canvas.offsetLeft;
    Y = e.clientY - canvas.offsetTop;
}

canvas.addEventListener("mousedown", (e)=>{
    e.preventDefault();
    setPosition(e);
    DRAWING = true;
});

canvas.addEventListener("mouseup", (e) => {
    e.preventDefault();
    DRAWING = false;
});

canvas.addEventListener("mouseout", (e) => {
    e.preventDefault();
    DRAWING = false;
});

canvas.addEventListener("mousemove", (e) => {
    if (e.buttons === 1 && DRAWING) {
        setPosition(e);
        ctx.beginPath();
        ctx.strokeStyle = colorPicker.value;
        ctx.moveTo(pX, pY);
        ctx.lineTo(X, Y);
        ctx.stroke();
        ctx.closePath();
    }
});

