import sharp from 'sharp';
import fs from 'fs';
import path from 'path';

/**
 * Make white / light background transparent for an image.
 * Uses sharp raw pixel buffer manipulation to create smooth alpha anti-aliasing.
 */
async function makeTransparent(inputPath, outputPath, options = {}) {
    const {
        threshold = 240, // brightness above this starts becoming transparent
        feather = 25, // smooth gradient transition
        crop = null,
    } = options;

    let image = sharp(inputPath);
    if (crop) {
        image = image.extract(crop);
    }

    const { data, info } = await image
        .ensureAlpha()
        .raw()
        .toBuffer({ resolveWithObject: true });

    const width = info.width;
    const height = info.height;
    const channels = info.channels; // 4 (RGBA)

    // Flood fill or color-key from edges to avoid removing white clothing/eyes inside
    // For isolated character portraits with pure/light backgrounds, let's sample edges
    for (let i = 0; i < data.length; i += channels) {
        const r = data[i];
        const g = data[i + 1];
        const b = data[i + 2];

        // Check if color is close to white / light gray
        // Minimum color component
        const minColor = Math.min(r, g, b);
        const maxColor = Math.max(r, g, b);
        const saturation = maxColor > 0 ? (maxColor - minColor) / maxColor : 0;

        // If high brightness and low saturation (white/light grey background)
        const brightness = (r + g + b) / 3;

        if (brightness >= threshold && saturation < 0.15) {
            // Completely transparent or feathered
            const diff = brightness - threshold;
            const factor = Math.min(1, diff / feather);
            data[i + 3] = Math.round(data[i + 3] * (1 - factor));
        } else if (brightness >= threshold - 10 && saturation < 0.1) {
            const factor = (brightness - (threshold - 10)) / 10;
            data[i + 3] = Math.round(data[i + 3] * (1 - factor * 0.7));
        }
    }

    await sharp(data, {
        raw: {
            width,
            height,
            channels: 4,
        },
    })
        .png({ compressionLevel: 9 })
        .toFile(outputPath);

    console.log(`Processed: ${outputPath}`);
}

async function run() {
    const brainDir =
        'C:\\Users\\Developer-2\\.gemini\\antigravity-ide\\brain\\78581f0f-c909-438a-9e47-4ef4255de577';
    const publicImages = 'd:\\test\\brightlearners\\public\\images';

    // 1. Class 1 girl
    const class1Source = path.join(
        brainDir,
        'class_1_mascot_1791888104864.jpg',
    );
    if (fs.existsSync(class1Source)) {
        await makeTransparent(
            class1Source,
            path.join(publicImages, 'class-1-girl.png'),
            {
                threshold: 235,
                feather: 20,
            },
        );
    }

    // 2. Class 2 boy
    const class2Source = path.join(brainDir, 'class_2_boy_1791885920138.jpg');
    if (fs.existsSync(class2Source)) {
        await makeTransparent(
            class2Source,
            path.join(publicImages, 'class-2-boy.png'),
            {
                threshold: 220,
                feather: 20,
            },
        );
    }

    // 3. Class 3 student
    const class3Source = path.join(
        brainDir,
        'class_3_student_1791885960471.jpg',
    );
    if (fs.existsSync(class3Source)) {
        await makeTransparent(
            class3Source,
            path.join(publicImages, 'class-3-student.png'),
            {
                threshold: 235,
                feather: 20,
            },
        );
    }

    // 4. Membership boy
    const membershipSource = path.join(
        brainDir,
        'membership_boy_1791885986576.jpg',
    );
    if (fs.existsSync(membershipSource)) {
        await makeTransparent(
            membershipSource,
            path.join(publicImages, 'membership-boy.png'),
            {
                threshold: 235,
                feather: 20,
            },
        );
    }

    console.log('All transparent images generated successfully!');
}

run().catch(console.error);
